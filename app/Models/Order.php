<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = mb_strtoupper($value, 'UTF-8');
    }
    
    protected $guarded = ['created_at', 'updated_at'];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class)->orderBy('id', 'asc')->orderBy('status', 'desc');
    }

    public function pedidospendientes(): HasMany
    {
        return $this->hasMany(Pedido::class)->where('status', 0)->orderBy('status', 'desc');
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }
}
