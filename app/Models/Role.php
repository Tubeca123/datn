<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'isactive',
    ];
    protected $primaryKey = 'id';
    protected $table = 'role';
    public $timestamps = false;
}
