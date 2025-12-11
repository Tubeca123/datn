<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'about',
        'position',
        'status',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
        "parent_id",
        "image"
    ];
    protected $primaryKey = 'id';
    protected $table = 'categories';
    public $timestamps = false;
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
