<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Category;
use App\Api\ApiMessages;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->paginate(10);

        return response()->json([
            'data' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        try{

            $category = $this->category->create($data);

            return response()->json([
                'data' => [
                    'msg' => 'Categoria cadastrada com sucesso!',
                    'data' => $data
                ]
            ], 201);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors'=>$message->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $category = $this->category->findOrFail($id);
            return response()->json([
                'data'=> [
                    'data' => $category
                ]
            ], 200);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['error'=>$message->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try{
            $category = $this->category->findOrfail($id);

            $category->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Categoria atualizada com sucesso!',
                    'data' => $data
                ]
            ], 200);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors'=>$message->getMessage()], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $category = $this->category->findOrFail($id);
            $category->delete();

            return response()->json([
                'data' => [
                    'msg'=> 'Categoria excluída com sucesso!'
                ]
            ]);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors'=>$message->getMessage()]);
        }
    }

    public function realStates($id)
    {
        try{
            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category->realStates
            ], 200);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json(['errors'=> $message->getMessage()], 401);
        }
    }
}
