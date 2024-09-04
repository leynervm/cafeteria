<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Mesa extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = mb_strtoupper($value, 'UTF-8');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function scopeMesasLogin($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
