<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::select('product_id', 'product_name', 'product_price', 'product_image', 'category_id', 'seller_id', 'isdelete')
        ->with('productCategory')
        ->get();

        return response()->json($product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->product_image == '') {
            $fileName = 'no-thumbnail.jpg';
        } else if(strlen($request->product_image) > 30) {
            
            $exploded = explode(',', $request->product_image);

            $decoded = base64_decode($exploded[1]);
    
            if(str_contains($exploded[0], 'jpg')) {
                $extension = 'jpg';
            } else if(str_contains($exploded[0], 'jpeg')) {
                $extension = 'jpeg';
            } else {
                $extension = 'png';
            }
    
            $fileName = Str::random(10).'.'.$extension;
    
            $path = public_path().'/images/products/'.$fileName;
    
            file_put_contents($path,$decoded);

        } else {
            $fileName = $request->product_image;
        }

        $product = $request->isMethod('put') ? Product::where('product_id', $request->product_id)->first() : new Product;

        $image_path = public_path() . '/images/products/' . $product->product_image;

        if(\File::exists($image_path) && $product->product_image != 'no-thumbnail.jpg' && strlen($request->product_image) > 30) {
            \File::delete($image_path);
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_price' => 'required',
            'category_id' => 'required',
            'seller_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status_code'=>400, 
                'message'=>'Bad Request',
            ]);
        }

        $product->product_id = $request->product_id;
        $product->product_name = $request->product_name;
        $product->product_price = $request->product_price;
        $product->product_image = $fileName;
        $product->category_id = $request->category_id;
        $product->seller_id = $request->seller_id;

        $product->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'Product created'
        ]);
    }

    public function sellerProduct($seller_id) {
        $product = Product::select('product_id', 'product_name', 'product_price', 'product_image', 'category_id', 'seller_id', 'isdelete')
        ->with('productCategory')
        ->where('seller_id', $seller_id)
        ->get();

        return response()->json($product);
    }

}
