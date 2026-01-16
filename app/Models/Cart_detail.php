<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Cart_detail extends Model
{
    use HasFactory;

    protected $table = 'cart_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'inventory_id',
        'code',
        'product_unit_id',
        'price',
        'quantity',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive'
    ];
    protected $casts = [
        'quantity' => 'float',
        'price' => 'float',
    ];
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
}
