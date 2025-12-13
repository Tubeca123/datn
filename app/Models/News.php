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
        'image',
        'category_id',
        'description',
        'content',
        'title',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive',
    ];

    protected $casts = [
        'create_date' => 'datetime',
        'update_date' => 'datetime',
    ];

    public function news_categories()
    {
        return $this->belongsToMany(News_category::class, 'news_category', 'news_id', 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(News_category::class, 'category_id');
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
