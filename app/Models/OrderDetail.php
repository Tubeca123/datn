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
        'quantity',
        'isactive',
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
}
