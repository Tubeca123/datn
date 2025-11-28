<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Unit extends Model
{
    use HasFactory;

    protected $table = 'unit';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'create_date',
        'create_by',
        'update_date',
        'update_by',
    ];
    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class, 'unit_id');
    }
}
