<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formapago extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = mb_strtoupper($value, 'UTF-8');
    }

    public function isDefault()
    {
        return $this->belongsTo(Formapago::class)->where('default', 1);
    }
    
}
