<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHistory extends Model
{
    use HasFactory;

    protected $table = 'order_history';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'total',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    public function details()
    {
        return $this->hasMany(OrderDetailHistory::class, 'order_history_id');
    }
}
