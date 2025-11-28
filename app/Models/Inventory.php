<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'product_unit_id',
        'code',
        'date_end',
        'import_quantity',
        'stock_quantity',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
    ];

    protected $casts = [
        'date_end' => 'date',
        'create_date' => 'datetime',
        'update_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    /**
     * Trả về phần trăm còn lại trong lô (ví dụ: 100 -> 50 => 50%)
     */
    public function remainingPercent(): float
    {
        if ($this->import_quantity <= 0) return 0.0;
        return round(($this->stock_quantity / $this->import_quantity) * 100, 2);
    }
}
