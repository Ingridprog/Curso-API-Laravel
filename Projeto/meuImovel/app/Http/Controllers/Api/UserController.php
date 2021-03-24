<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Api\ApiMessages;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->paginate(10);

        return response()->json(['data'=> $users], 200);
    }

    public function show($id)
    {
        try{
            $user = $this->user->with('userProfile')->findOrFail($id);
            $user->userProfile->social_networks = unserialize($user->userProfile->social_networks);

            return response()->json(['data'=> $user], 200);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors'=> $message->getMessage()], 401);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if(!$request->has('password') || !$request->get('password')){
            $message = new ApiMessages('É necessário fazer uma senha para o usuário');
            return response()->json(['errors'=> $message->getMessage()], 401);
        }

        Validator::make($data, [
            'phone' => 'required',
            'mobile_phone' => 'required'
        ])->validate();

        try{

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);

            $user->userProfile()->create([
                'phone' => $data['phone'],
                'mobile_phone' => $data['mobile_phone']
            ]);

            return response()->json([
                'data'=>[
                    'msg'=>'Usuário Criado com sucesso!',
                    'data'=> $data
                ]
            ], 201);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors'=> $message->getMessage()], 401);
        }
    }

    public function update($id, Request $request)
    {
        $data = $request->all();

        if($request->has('password') || $request->get('password')){
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        Validator::make($data, [
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required'
        ])->validate();

        try{

            $userProfile = $data['profile'];
            $userProfile['social_networks'] = serialize($userProfile['social_networks']);

            $user = $this->user->findOrFail($id);
            $user->update($data);

            $user->userProfile()->update($userProfile);

            return response()->json([
                'data'=>[
                    'msg' => 'Usuário atualizado com sucesso!',
                    'data' => $data
                ]
            ], 200);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['error'=>$message->getMessage()], 401);
        }
    }

    public function destroy($id)
    {
        try{
            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json(['msg'=> 'Usuário excluído com sucesso!'], 200);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors' => $message->getMessage()], 401);
        }
    }
}
