<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// Utiliza o retorno do eloquent(models) para converter o recurso em json
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        // return [
        //     'name'=> $this->name,
        //     'price'=> $this->price,
        //     'slug'=> $this->slug
        // ];

        // Retorna todos os dados 
        return $this->resource->toArray();
    }
}
