<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\RealState;
use App\Api\ApiMessages;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        // Mostrar os imoveis do usuário
        $realState = auth('api')->user()->real_state()->paginate(10);

        return response()->json($realState, 200);
    }

    public function show($id)
    {
       try{
            // $realState = $this->realState->with('photos')->findOrFail($id);
            $realState = auth('api')->user()->real_state()->with('photos')->findOrFail($id)->makeHidden('thumb');

            return response()->json(['data' => $realState]);
       }catch(\Exception $e){
           $message = new ApiMessages($e->getMessage());
           return response()->json([$message->getMessage()], 401);
       }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        $data['user_id'] = auth('api')->user()->id; 

        try{

            $realState = $this->realState->create($data); //Mass Asigment

            if($data['categories'] && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            // storage->app->public->images
            if($images){

                $imagesUploaded = [];

                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo'=> $path, 'is_thumb' => false];
                    
                    $realState->photos()->createMany($imagesUploaded);
                }
            }

            return response()->json([
                'data'=>[
                    'msg'=> 'Imóvel cadastrado com sucesso!'
                ]
            ]);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json([$message->getMessage()], 401); 
        }
        
    }

    public function update($id, RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try{
            $realState = auth('api')->user()->real_state()->findOrFail($id);
            $realState->update($data);

            if($data['categories'] && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            if($images){

                $imagesUploaded = [];

                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo'=> $path, 'is_thumb' => false];
                    
                    $realState->photos()->createMany($imagesUploaded);
                }
            }

            return response()->json([
                'msg'=> 'Imóvel atualizado com sucesso!',
                'data'=>$data
            ]);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json([$message->getMessage()], 401);
        }
    }

    public function destroy($id)
    {
        try{
            $realState = auth('api')->user()->real_state()->findOrFail($id);
            $realState->delete();

            return response()->json([
                'msg' => 'Imóvel excluído com sucesso!'
            ]);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json([$message->getMessage()], 401);
        }
    }
}
