<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function cartCount($buyer_id)
    {
        $order = OrderProduct::select('orders.*', 'order_products.*')
        ->join('orders', 'orders.order_id', '=', 'order_products.order_id')
        ->where('orders.order_status', 'Pending')
        ->where('orders.buyer_id', $buyer_id)
        ->get();

        $orderCount = $order->count();

        return response()->json($orderCount);
    }

    public function cartBuyer($buyer_id) {
        $order = OrderProduct::select('orders.*', 'order_products.*')
        ->join('orders', 'orders.order_id', '=', 'order_products.order_id')
        ->where('orders.order_status', 'Pending')
        ->where('orders.buyer_id', $buyer_id)
        ->with(['orderProduct' => function($query) {
            $query->select('users.*', 'products.*')
            ->join('users', 'users.id', '=', 'products.seller_id');
        }])
        ->get();

        return response()->json($order);
    }

    public function deleteCart($product_id, $buyer_id) {

        $cart = OrderProduct::join('orders', 'orders.order_id', '=', 'order_products.order_id')
        ->where('orders.order_status', 'Pending')
        ->where('orders.buyer_id', $buyer_id)
        ->where('product_id', $product_id)
        ->delete();

        return response()->json([
            'status_code' => 200, 
            'message' => 'Cart Deleted'
        ]);
    }

    public function addToCart(Request $request)
    {
        $exists = Order::select('order_id')
        ->where('order_status', 'Pending')
        ->where('buyer_id', $request->buyer_id)
        ->first();
        
        if($exists === null) {
            $order = new Order;
    
            $validator = Validator::make($request->all(), [
                'buyer_id' => 'required',
                'seller_id' => 'required'
            ]);
    
            if($validator->fails())
            {
                return response()->json([
                    'status_code'=>400, 
                    'message'=>'Bad Request',
                ]);
            }
    
            $order->buyer_id = $request->buyer_id;
            $order->seller_id = $request->seller_id;
    
            $order->save();
            $lastid = $order->id;
        } else {
            $lastid = $exists['order_id'];
        }

        $orderDetails = new OrderProduct;
        $orderDetails->order_id = $lastid;
        $orderDetails->product_id = $request->product_id;
        $orderDetails->product_qty = 1;
        $orderDetails->total_price = $request->post_product['product_price'];

        $orderDetails->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'Added to cart'
        ]);
    }

    public function productSale($seller_id) {
        $product = OrderProduct::select('order_products.*', 'products.*')
        ->leftJoin('orders', 'orders.order_id', '=', 'order_products.order_id')
        ->leftJoin('products', 'products.product_id', '=', 'order_products.product_id')
        ->where('products.seller_id', $seller_id)
        ->where('orders.order_status', 'Sold')
        ->orderBy('order_products.created_at', 'asc')
        ->get();
        return response()->json($product);
    }
}
