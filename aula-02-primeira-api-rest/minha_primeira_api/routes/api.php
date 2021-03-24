<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (Request $request) {

    // dump and die - pegar todos os cabeçalhos da requisição
    dd($request->headers->all());

    // dd($request->headers->get('Authorization'));

    $response = new \Illuminate\Http\Response(json_encode(['msg'=> 'Minha primeira resposta de API!']));
    
    // Altera o cabeçalho da resposta - Cabeçalhos influenciam no retorno e no evio de requisições
    $response->header('Content-type', 'application/json');

    return $response;
});

// PRODUCTS ROUTES

// Agrupar com namespace Api
Route::namespace('Api')->group(function(){

    // Routes Products
    Route::prefix('products')->group(function(){
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::post('/', 'ProductController@save')->middleware('auth.basic');
        Route::put('/', 'ProductController@update');
        Route::delete('/{id}', 'ProductController@delete');
    });
       
    // Recursos para API: Controller como recurso
    Route::resource('/users', 'UserController');
});

