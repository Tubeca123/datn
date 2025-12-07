<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'inventory_id',
        'code',
        'product_unit_id',
        'price',
        'quantity',
        'isactive',
    ];
    protected $casts = [
        'quantity' => 'float',
        'price' => 'float',
    ];
    // Quan hệ tới order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Quan hệ tới product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    /**
     * Tính tổng tiền của chi tiết này
     */
    public function totalAmount(): float
    {
        return $this->quantity * $this->getPrice();
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    /**
     * Lấy giá - ưu tiên giá đã lưu, nếu không có thì lấy từ product_unit
     */
    public function getPrice(): float
    {
        // Nếu đã có price được lưu (snapshot), dùng nó
        if ($this->price !== null && $this->price > 0) {
            return $this->price;
        }

        // Nếu không, lấy từ productUnit hiện tại
        if ($this->productUnit) {
            return $this->productUnit->price_sale ?? 0;
        }

        return 0;
    }

    /**
     * Lấy tên đơn vị
     */
    public function getUnitNameAttribute(): string
    {
        return $this->productUnit?->unit?->name ?? 'N/A';
    }

    /**
     * Lấy tên sản phẩm
     */
    public function getProductNameAttribute(): string
    {
        return $this->product?->name ?? 'Sản phẩm không tồn tại';
    }

    /**
     * Lấy số viên cơ sở (base unit) từ quantity
     */
    public function getBaseQuantity(): int
    {
        if (!$this->productUnit) return 0;

        $qtyPerUnit = max(1, $this->productUnit->quantity_per_unit ?? 1);
        return (int) ($this->quantity * $qtyPerUnit);
    }
}
