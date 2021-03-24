<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectFilter($filters)
    {
        // FILTROS de campos - ?fields=name, price
        $this->model = $this->model->selectRaw($filters);
    }

    public function selectConditions($conditions)
    {
        $expressions = explode(';', $conditions);

        foreach($expressions as $e){
            $exp = explode(':', $e);
            // Dois parametros o operador padrão é o igual
            $this->model = $this->model->where($exp[0], $exp[1], $exp[2]);
        }
    }

    public function getResult()
    {
        return $this->model;
    }
}

?>