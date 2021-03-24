<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{

    // HATEOAS
    protected $appends = ['_links', 'thumb'];

    protected $table = 'real_state';
    protected $fillable = [
        'user_id', 'title', 'description', 'content',
        'price', 'slug', 'bathrooms', 'bedrooms', 'property_area',
        'total_property_area' 
    ];

    //Accessors HATEOAS - Entrega o link da single de cada real_state - Hipermidia como estado de mecanismo da aplicação
    public function getLinksAttribute()
    {
        return [
            'href' => route('real_states.real-states.show', $this->id),
            'rel'  => 'Imóveis'
        ];
    }

    public function getThumbAttribute()
    {
        $photo = $this->photos->where('is_thumb', true);

        if(!$photo->count()) return null;

        return $photo->first()->photo;
    }

    public function user()
    {
        return $this->belongsTo(User::class/*, 'outro_nome'*/); //user_id
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'real_state_categories', 'real_state_id', 'categories_id');
    }

    public function photos(){
        return $this->hasMany(RealStatePhoto::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
