<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageProduct extends Model
{
    use HasFactory;

    protected $table = 'image_product';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'src',
        'position',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
