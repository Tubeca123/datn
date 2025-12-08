<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role_id',
        'password',
        'isactive',
    ];
    protected $primaryKey = 'id';
    protected $table = 'users';
    public $timestamps = false;
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
