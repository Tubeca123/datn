<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetailHistory extends Model
{
    use HasFactory;

    protected $table = 'order_detail_history';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'order_history_id',
        'product_id',
        'inventory_id',
        'code',
        'product_unit_id',
        'price',
        'quantity'
    ];
    public function orderhistory()
    {
        return $this->belongsTo(OrderHistory::class, 'order_history_id');
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
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    public function getUnitNameAttribute(): string
    {
        return $this->productUnit?->unit?->name ?? 'N/A';
    }
    public function getPrice(): float
    {

        if ($this->price !== null && $this->price > 0) {
            return $this->price;
        }


        if ($this->productUnit) {
            return $this->productUnit->price_sale ?? 0;
        }

        return 0;
    }
    public function totalAmount(): float
    {
        return $this->quantity * $this->getPrice();
    }
}
