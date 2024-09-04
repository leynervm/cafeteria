<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notapedido extends Model
{
    use HasFactory;

    public $guarded = ['created_at', 'updated_at'];

    public function detallenotas(): HasMany
    {
        return $this->hasMany(Detallenota::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
