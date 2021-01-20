<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;

class SaleController extends Controller
{

    public function addPayment($order_id, $payment_typeid) {
        $payment = new Sale;

        $payment->order_id = $order_id;
        $payment->payment_typeid = $payment_typeid;

        $payment->save();

        $order = Order::where('order_id', $order_id)->first();

        $order->order_status = 'Sold';
        $order->save();

        return response()->json([
            'status_code' => 200, 
            'message' => 'Paid successfull'
        ]);
    }
}
