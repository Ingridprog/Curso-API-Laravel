<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RealState;
use App\Repository\RealStateRepository;
use App\Api\ApiMessages;

class RealStateSearchController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index(Request $request)
    {
        $realState = $this->realState->paginate(10);

        $repository = new RealStateRepository($this->realState);

        if($request->has('conditions')){
            
            $repository->selectConditions($request->get('conditions'));
        }

        if($request->has('fields')){
            $repository->selectFilter($request->get('fields'));
        }

        $repository->setLocation($request->all(['state', 'city']));

        return response()->json([
            'data' => $repository->getResult()->paginate(10)
        ], 200);
    }

    public function show($id)
    {
        try{
            $realState = $this->realState->with('address')->with('photos')->findOrFail($id);

            return response()->json([
                'data' => $realState
            ], 200);
        }catch(\Exeception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors' => $message->getMessage()]);
        }
    }
}
