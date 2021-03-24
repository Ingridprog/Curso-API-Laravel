<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        
        return[
            'data' => $this->collection,
            'extra' => 'Data adicional'
        ];
    }

    // Informações extras
    public function with($request)
    {
        return ['extra_information' => 'Another data!'];
    }
}
