<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\RealStatePhoto;
use App\Api\ApiMessages;

class RealStatePhotoController extends Controller
{

    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($id, $realStateId)
    {
        try{
            // Primeiro remove a thumb atual
            $photo = $this->realStatePhoto
                ->where('real_state_id', $realStateId)
                ->where('is_thumb', true);

            if($photo->count()) $photo->first()->update(['is_thumb'=>false]);
            

            // Atualiza a nova thumb
            $photo = $this->realStatePhoto->find($id);
            $photo->update(['is_thumb'=> true]);

            return response()->json([
                'data' => [
                    'msg' => 'Thumb atualizada com sucesso!'
                ]
            ]);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json([$message->getMessage()], 401);
        }
    }

    public function destroy($id)
    {
        try{

            $photo = $this->realStatePhoto->find($id);

            if($photo->is_thumb){
                $message = new ApiMessages('Não é possivel remover foto de thumb! Selecione outra thumb e remova a foto desejada!');
                return response()->json(['errors' => $message->getMessage()]);
            }
           
            if($photo){
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                'data' => [
                    'msg' => 'Foto excluída com sucesso!'
                ]
            ]); 

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors' => $message->getMessage()]);
        }
    }
}
