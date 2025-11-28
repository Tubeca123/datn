<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brand';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
