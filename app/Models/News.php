<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'image',
        'category_id',
        'description',
        'content',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];

    public function news_categories()
    {
        return $this->belongsToMany(News_category::class, 'news_category', 'news_id', 'category_id');
    }
}
