<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'link',
        'position',
        'image',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive'
    ];
    protected $primaryKey = 'id';
    protected $table = 'banner';
    public $timestamps = false;
}
