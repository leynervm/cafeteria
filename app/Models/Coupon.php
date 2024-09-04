<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $guarded = ['created_at', 'updated_at'];

    public function setCodeAttribute($value)
    {
        $this->attributes["code"] = mb_strtoupper($value, 'UTF-8');
    }
}
