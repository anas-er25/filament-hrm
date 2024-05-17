<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    // protected $fillable = ['country_id', 'state_id', 'city_id', 'departement_id', 'first_name', 'last_name', 'middle_name', 'address', 'zip_code', 'date_of_birth', 'date_hired'];

    protected $guarded = [];
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
    
    public function state(): BelongsTo
    
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function team(): BelongsTo
    
    {
        return $this->belongsTo(Team::class);
    }
}
