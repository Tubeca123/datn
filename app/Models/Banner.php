<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner';      
    protected $primaryKey = 'id';     
    public $timestamps = false;      

    protected $fillable = [
        'content',
        'link',
        'image',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
        'isactive'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
