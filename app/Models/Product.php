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
    /**
     * Tăng tồn kho (nhập) — $quantity là số lượng theo unit (vd: 5 hộp)
     * Tạo 1 bản ghi inventory (một lô)
     */
    public function increaseStock(int $productUnitId, int $quantity, ?string $code = null, $date_end = null, $create_by = null): Inventory
    {
        $pu = ProductUnit::findOrFail($productUnitId);

        $baseQty = $quantity * max(1, $pu->quantity_per_unit); // convert to base unit

        $inventory = Inventory::create([
            'product_id' => $this->id,
            'product_unit_id' => $pu->id,
            'code' => $code ?? 'IN' . strtoupper(uniqid()),
            'date_end' => $date_end,
            'import_quantity' => $baseQty,
            'stock_quantity' => $baseQty,
            'create_date' => now(),
            'create_by' => $create_by,
            'update_date' => null,
            'update_by' => null,
        ]);

        return $inventory;
    }

    /**
     * Giảm tồn (bán) theo FIFO.
     * $productUnitId: đơn vị bán (vd: hộp)
     * $quantity: số lượng bán theo đơn vị đó (vd: 2 hộp)
     *
     * Trả về true nếu thành công, ném Exception nếu không đủ hàng.
     */
    public function decreaseStockFIFO(int $productUnitId, int $quantity): bool
    {
        $pu = ProductUnit::findOrFail($productUnitId);
        $neededBase = $quantity * max(1, $pu->quantity_per_unit);

        return DB::transaction(function () use ($neededBase) {
            // lock các bản ghi inventory để tránh race condition
            $batches = $this->inventory()
                ->where('stock_quantity', '>', 0)
                ->orderBy('create_date', 'asc')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->get();

            $remaining = $neededBase;

            foreach ($batches as $batch) {
                if ($remaining <= 0) break;

                if ($batch->stock_quantity >= $remaining) {
                    $batch->stock_quantity = $batch->stock_quantity - $remaining;
                    $batch->save();
                    $remaining = 0;
                    break;
                } else {
                    $remaining -= $batch->stock_quantity;
                    $batch->stock_quantity = 0;
                    $batch->save();
                }
            }

            if ($remaining > 0) {
                // nếu không đủ, rollback bằng cách ném exception
                throw new Exception('Không đủ tồn kho (cần ' . $neededBase . ' đơn vị cơ sở, thiếu ' . $remaining . ').');
            }

            return true;
        });
    }
}
