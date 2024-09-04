<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detallenota extends Model
{
    use HasFactory;

    public $guarded = ['created_at', 'updated_at'];

    public function notapedido()
    {
        return $this->belongsTo(Notapedido::class);
    }

    // public function detalleagregados()
    // {
    //     return $this->hasMany(Detalleagregado::class);
    // }

    public function agregado()
    {
        return $this->belongsTo(Agregado::class);
    }
}
