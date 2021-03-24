<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'Adresses';

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongTo(State::class);
    }

    public function real_state()
    {
        return $this->hasOne(RealState::class);
    }
}
