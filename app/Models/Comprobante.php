<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprobante extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $guarded = ['created_at', 'updated_at'];

    public function setCodeserieAttribute($value)
    {
        $this->attributes["codeserie"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setSeriecompletaAttribute($value)
    {
        $this->attributes["seriecompleta"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setLeyendaAttribute($value)
    {
        $this->attributes["leyenda"] = mb_strtoupper($value, 'UTF-8');
    }

    public function comprobanteitems(): HasMany
    {
        return $this->hasMany(Comprobanteitem::class)->orderBy('item', 'asc');
    }

    public function tribute(): BelongsTo
    {
        return $this->belongsTo(Tribute::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    //USO EEL SEGUNDO ATRIBUTO PORQUE HAY UN CAMPO SERIE EN COMPROBANTE QUE ME IMPIDE TENER RELACION SERIE
    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function formapago(): BelongsTo
    {
        return $this->belongsTo(Formapago::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
