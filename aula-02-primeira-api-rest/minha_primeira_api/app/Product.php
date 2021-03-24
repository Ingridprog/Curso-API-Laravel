<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Campos que serão permitidos a inserção de dados  
    protected $fillable = [
        'name', 'price', 'description', 'slug'
    ];
}
