<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name', 'description', 'slug'
    ];

    public function realStates()
    {
        return $this->belongsToMany(RealState::class, 'real_state_categories', 'categories_id','real_state_id');
    }

}
