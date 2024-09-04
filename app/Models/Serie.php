<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['created_at', 'updated_at'];

    public function setSerieAttribute($value)
    {
        $this->attributes["serie"] = mb_strtoupper($value, 'UTF-8');
    }

    public function comprobantes(): BelongsTo
    {
        return $this->belongsTo(Comprobante::class);
    }
}
