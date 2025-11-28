<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProductUnit extends Model
{
    use HasFactory;

    protected $table = 'product_unit';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'unit_id',
        'product_id',
        'price_sale',
        'price_import',
        'quantity_per_unit'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    
    public function pricePerBaseUnit(): float
    {
        $q = max(1, (int) $this->quantity_per_unit);
        return $this->price_import ? round($this->price_import / $q, 2) : 0.0;
    }
}
