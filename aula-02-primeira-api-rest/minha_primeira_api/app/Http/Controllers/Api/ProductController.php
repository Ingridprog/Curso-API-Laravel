<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Repository\ProductRepository;

class ProductController extends Controller
{
    private $product;
    
    // Instancia do Model Product 
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $products = $this->product;

        $productRepository = new ProductRepository($products);

        // Condições na filtragem - &conditons=name:Teste,price:38.99
        if($request->has('conditions')){
            
            $productRepository->selectConditions($request->get('conditions'));
        }

        // Recursos para API: Paginação
        // $products = $this->product->paginate(10);

        // Retorna com o header correto
        // return response()->json($products);

        if($request->has('fields')){
            $productRepository->selectFilter($request->get('fields'));
        }

        // Recursos para API
        return new ProductCollection($productRepository->getResult()->paginate(10));
    }

    public function show($id)
    {
        $product = $this->product->find($id);

        // return response()->json($product);

        // Recursos para API: ProductResource passa o model e retorna o json 
        return new ProductResource($product);
    }

    public function save(ProductRequest $request)
    {
        $data = $request->all();
        $product = $this->product->create($data);
        return response()->json($product);
    }

    public function update(ProductRequest $request)
    {
        $data = $request->all();
        $product = $this->product->find($data['id']);
        $product->update($data);

        return response()->json($product);
    }

    public function delete($id)
    {
        $product = $this->product->find($id);
        $product->delete();

        return response()->json(['data'=> ['msg'=> 'Produto deletado com sucesso!']]);
    }
}
