<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News_category extends Model
{
    use HasFactory;

    protected $table = 'News_category';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];
    
    function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }


    
}
