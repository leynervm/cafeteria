<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = mb_strtoupper($value, 'UTF-8');
    }

    public function agregados()
    {
        return $this->belongsToMany(Agregado::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }
}
