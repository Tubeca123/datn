<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News_category extends Model
{
    use HasFactory;

    protected $table = 'news_categories';
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
    
    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('isactive', 1);
    }
}
