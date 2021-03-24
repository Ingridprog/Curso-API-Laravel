<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Api\ApiMessages;

class LoginJwtController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        Validator::make($credentials, [
           
            'email'    => 'required|string',
            'password' => 'required|string',
            
        ])->validate();

        // attempt - Tenta logar o usuário
        if(!$token = auth('api')->attempt($credentials)){
            $message = new ApiMessages('Unauthorized');
            return response()->json([$message->getMessage()], 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }

    // tem que informar o token para identificar o usuário(única maneira)
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout successfuly!'], 200);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json(['token' => $token]);
    }
}
