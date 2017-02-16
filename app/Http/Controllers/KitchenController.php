<?php

namespace App\Http\Controllers;

use App\Helpers\Socket;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function __construct()
    {
        $this->middleware('kitchen_auth');
    }

    public function getOrdersKitchen(){
        $orders = Order::where('status', '=', 'new')
            ->orWhere('status', '=', 'cooking')
            ->with('orderItem.menu', 'user')
            ->orderBy('id', 'DESC')
            ->get();
        return $orders;
    }

    public function doPrepare(Request $request)
    {
        $id = $request->get('id');
        $time = $request->get('time');
        $orderItem = OrderItem::where('id', '=', $id)
            ->first();
        if ($orderItem instanceof OrderItem){
            $orderItem->status = 'cooking';
            $orderItem->time = (new \DateTime('+'.$time.' minutes + 2 hours'));
            $orderItem->save();
            Socket::send(['role'=>2,'refresh'=>true]);
            return $orderItem;
        }
    }

    public function doReady(Request $request)
    {
        $id = $request->get('id');
        $orderItem = OrderItem::where('id', '=', $id)
            ->first();
        if (!($orderItem instanceof OrderItem)){
            return true;
        }
        $orderItem->status = 'done';
        $orderItem->save();

        $order = $orderItem->order;
        $i = 0;
        if ($order instanceof Order){
            $orderItems = $order->orderItem;
            foreach ($orderItems as $item){
                if ($item->status == 'done'){
                    $i++;
                }
                if ($i === count($orderItems)){
                    $order->status = 'done';
                    $order->save();
                }
            }
        }
        Socket::send(['role'=>2,'refresh'=>true]);
    }

}
