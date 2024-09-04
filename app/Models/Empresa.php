<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes["direccion"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setZonaAttribute($value)
    {
        $this->attributes["zona"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setUrbanizacionAttribute($value)
    {
        $this->attributes["urbanizacion"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setDistritoAttribute($value)
    {
        $this->attributes["distrito"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setProvinciaAttribute($value)
    {
        $this->attributes["provincia"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setDepartamentoAttribute($value)
    {
        $this->attributes["departamento"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setEstadoAttribute($value)
    {
        $this->attributes["estado"] = mb_strtoupper($value, 'UTF-8');
    }

    public function setCondicionAttribute($value)
    {
        $this->attributes["condicion"] = mb_strtoupper($value, 'UTF-8');
    }
}
