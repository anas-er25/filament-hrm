<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    protected $fillable = ["name", "state_id"];
    
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
