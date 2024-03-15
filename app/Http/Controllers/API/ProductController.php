<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth::user());
        $products = Product::with('user')->get();
        $title="All Products";
        return view('showproducts',compact('products','title'));
        // return response()->json(['data'=>$products]);
        // return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'product_name' => 'required',
            'product_description' => 'required',
            'product_id' => 'required|unique:products',
            'product_category' => 'required',
            'available_quantity' => 'required',
            'enable_display' => 'required',
            'product_price' => 'required',
            'product_img'=>'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $productData['name']=$input['product_name'];
        $productData['detail']=$input['product_description'];
        $productData['product_id']=$input['product_id'];
        $productData['category']=$input['product_category'];
        $productData['quantity']=$input['available_quantity'];
        $productData['display']=$input['enable_display'];
        $productData['price']=$input['product_price'];
        $productData['productimage']=$input['product_img'];
        $productData['user_id']=Auth::user()->id;
        $product = Product::create($productData);

        return redirect()->route('products.index');
        // return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        return view('showProduct',compact('product'));
        // return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        // dd($product->user_id,Auth::user()->id);
        if($product->user_id!=Auth::user()->id)
        {
            $right='edit';
            return view('notAuthorized',compact('right'));
        }
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        return view('editProduct',compact('product'));
        // return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, Product $product)
    {
        if($product->user_id!=Auth::user()->id)
        {
            $right='update';
            return view('notAuthorized',compact('right'));
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return redirect()->route('products.show',$product->id);
        // return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }


    public function getAllMyProducts(){
        if(Auth::user()){
            // dd(Auth::user()->id);
            $products=[];
            $products = Product::where('user_id',Auth::user()->id)->get();
            // dd($products);
            $title="My products";
            return view('showproducts',compact('products','title'));
        }
        $right="view";
        return view('notAuthorized',compact('right'));
    }

    // public function recentProducts(){
    //     if(Auth::user()){
    //         // dd(Auth::user()->id);
    //         $products=[];
    //         $products = Product::where('user_id',Auth::user()->id)->get();
            
    //         $collection=collect($products);
    //         $sortedProducts=$collection->sortBy('created_at');
    //         $sortedProducts->values->all();

    //         return 
    //     }
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Product $product)
    {
        if($product->user_id!=Auth::user()->id)
        {
            $right='delete';
            return view('notAuthorized',compact('right'));
        }
        $product->delete();

        return redirect()->route('products.index');
    }

    
    public function deletedProducts(Request $request){
        
        $products=Product::onlyTrashed()->where('user_id',Auth::user()->id)->get();
        // dd($products);
        if(count($products)<=0)
        {
            $object='deleted products';
            return view('empty',compact('object'));
        }
        return view('showdeleted',compact('products'));
    }
    public function restoreProduct($id)
    {
        $product = Product::withTrashed()->find($id);
        if($product->user_id!=Auth::user()->id)
        {
            $right='restore';
            return view('notAuthorized',compact('right'));
        }
        $product->restore(); // This restores the soft-deleted post
        return redirect()->route('products.index');
    }

    public function deleteProductForever($id)
    {
        // If you have not deleted before
        $product = Product::withTrashed()->find($id);
        // dd($product);
        if($product->user_id!=Auth::user()->id)
        {
            $right='delete';
            return view('notAuthorized',compact('right'));
        }

        // If you have soft-deleted it before
        $product = Product::withTrashed()->find($id);

        $product->forceDelete(); // This permanently deletes the post for ever!
        // return redirect()->route('products.index');
        return response()->json(["success"=>true]);
        // return redirect()->route('deleted');
    }

}
