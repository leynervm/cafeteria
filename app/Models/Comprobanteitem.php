<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprobanteitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $guarded = ['created_at', 'updated_at'];

    public function setDescripcionAttribute($value)
    {
        $this->attributes["descripcion"] = mb_strtoupper($value, 'UTF-8');
    }

    public function comprobante(): BelongsTo
    {
        return $this->belongsTo(Comprobante::class);
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    
}
