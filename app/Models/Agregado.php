<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agregado extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = mb_strtoupper($value, 'UTF-8');
    }
}
