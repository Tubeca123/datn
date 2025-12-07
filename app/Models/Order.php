<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'total',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];

    // Một đơn có nhiều sản phẩm
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
