<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
        ->with('postProduct')
        ->get();

        return response()->json($post);
    }

    public function postProductInfo($product_id, $buyer_id)
    {
        if($buyer_id != 0) {
            $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
            ->where('product_id', $product_id)
            ->with(['postProduct' => function($query) {
                $query->select('order_products.*', 'orders.*', 'products.*')
                ->leftJoin('order_products', 'order_products.product_id', '=', 'products.product_id')
                ->leftJoin('orders', 'orders.order_id', '=', 'order_products.order_id');
            }])
            ->with('postSeller')
            ->get();
        } else {
            $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
            ->where('product_id', $product_id)
            ->with('postProduct')
            ->with('postSeller')
            ->get();
        }

        return response()->json($post);
    }

    public function buyerProductAll($buyer_id)
    {
        $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
        ->with(['postProduct' => function($query) use($buyer_id) {
            $query->select('order_products.*', 'orders.*', 'products.*')
            ->leftJoin('order_products', 'order_products.product_id', '=', 'products.product_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'order_products.order_id')
            ->where('orders.buyer_id', $buyer_id);
        }])
        ->with('postSeller')
        ->get();

        return response()->json($post);
    }

    public function approvedPost($buyer_id)
    {
        if($buyer_id != 0) {
            $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
            ->where('status', 'Approved')
            ->with(['postProduct' => function($query) {
                $query->select('order_products.*', 'orders.*', 'products.*')
                ->leftJoin('order_products', 'order_products.product_id', '=', 'products.product_id')
                ->leftJoin('orders', 'orders.order_id', '=', 'order_products.order_id');
            }])
            ->get();
        } else {
            $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
            ->where('status', 'Approved')
            ->with('postProduct')
            ->get();
        }

        return response()->json($post);
    }

    public function sellerPost($seller_id) 
    {
        $post = Post::select('post_id', 'product_id', 'title', 'description', 'status', 'seller_id')
        ->with('postProduct')
        ->where('seller_id', $seller_id)
        ->get();

        return response()->json($post);
    }
    
    public function store(Request $request)
    {
        $post = $request->isMethod('put') ? Post::where('post_id', $request->post_id)->first() : new Post;

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'title' => 'required',
            'product_id' => 'required',
            'seller_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status_code'=>400, 
                'message'=>'Bad Request',
            ]);
        }

        $post->post_id = $request->post_id;
        $post->product_id = $request->product_id;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->seller_id = $request->seller_id;

        $post->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'Post created'
        ]);
    }

    public function approveStatus(Request $request)
    {
        $post = Post::findOrFail($request->post_id);

        $post->status = "Approved";

        $post->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'Post approved'
        ]);
    }
}
