<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Exception;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'brand_id',
        'category_id',
        'manufacturer',
        'country',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
        'sold_count'
    ];
    public function images()
    {
        return $this->hasMany(ImageProduct::class, 'product_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }
    public function units()
    {
        return $this->hasMany(ProductUnit::class, 'product_id');
    }
    /**
     * Tổng tồn hiện tại (theo đơn vị cơ sở - ví dụ: viên)
     */
    public function totalStockBaseUnit(): int
    {
        return (int) $this->inventory()->sum('stock_quantity');
    }

    /**
     * Lấy số lượng quy đổi theo một unit cụ thể (ví dụ: trả về số Hộp còn lại)
     * Trả về array: ['units' => int, 'remainder_base' => int]
     */
    public function stockByProductUnit(int $productUnitId): array
    {
        $pu = $this->units()->where('id', $productUnitId)->first();
        if (!$pu) return ['units' => 0, 'remainder_base' => $this->totalStockBaseUnit()];

        $base = $this->totalStockBaseUnit();
        $units = (int) floor($base / max(1, $pu->quantity_per_unit));
        $remainder = $base - ($units * max(1, $pu->quantity_per_unit));

        return ['units' => $units, 'remainder_base' => $remainder];
    }
    
    public function increaseStock($product_unit_id, $quantity, $code, $date_end, $user_id)
    {
        $unit = ProductUnit::findOrFail($product_unit_id);

        // số viên trong mỗi đơn vị (ví dụ: 1 hộp = 10 vỉ, 1 vỉ = 10 viên → 100 viên)
        $qtyPerUnit = $unit->quantity_per_unit ?? 1;

        // tổng số viên cần cộng lại
        $baseQuantity = $quantity * $qtyPerUnit;

        Inventory::create([
            'product_id'      => $this->id,
            'code'            => $code,
            'date_end'        => $date_end,
            'import_quantity' => $baseQuantity,
            'stock_quantity'  => $baseQuantity,
            'create_date'     => now(),
            'create_by'       => $user_id,
        ]);
    }


    public function decreaseStockFIFO(int $productUnitId, int $quantity): array
    {
        $pu = ProductUnit::findOrFail($productUnitId);
        $neededBase = $quantity * max(1, $pu->quantity_per_unit);

        return DB::transaction(function () use ($neededBase, $pu) {

            $batches = $this->inventory()
                ->where('stock_quantity', '>', 0)
                ->orderBy('date_end', 'asc')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->get();

            $remaining = $neededBase;
            $usedBatches = [];

            foreach ($batches as $batch) {
                if ($remaining <= 0) break;

                $take = min($remaining, $batch->stock_quantity);

                // trừ tồn
                $batch->stock_quantity -= $take;
                $batch->save();

                // lưu lại lô đã dùng - QUAN TRỌNG: thêm quantity_per_unit để controller quy đổi
                $usedBatches[] = [
                    'code' => $batch->code,
                    'inventory_id' => $batch->id,
                    'quantity_base' => $take,
                    'quantity_per_unit' => $pu->quantity_per_unit  // ← THÊM ĐÂY
                ];

                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new Exception('Không đủ tồn kho (cần ' . $neededBase . ' đơn vị cơ sở, thiếu ' . $remaining . ').');
            }

            return $usedBatches;  // ← TRẢ LÔ ĐÃ XUẤT
        });
    }
}
