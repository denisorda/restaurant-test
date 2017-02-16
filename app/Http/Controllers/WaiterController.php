<?php

namespace App\Http\Controllers;

use App\Helpers\Result;
use App\Helpers\Socket;
use App\Menu;
use App\Order;
use App\OrderItem;
use App\User;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    public function __construct()
    {
        $this->middleware('waiter_auth');
    }

    public function menu()
    {
        $menu = Menu::where('is_deleted', '=', 0)
            ->get();
        return $menu;
    }

    public function sendOrder(Request $request)
    {
        $waiter = User::where('remember_token', '=', $request->header('Authorization'))
            ->first();
        $table = $request->get('number');
        $orderArr = $request->get('order');
        if (empty($table) || empty($orderArr)) {
            Result::error(422, 'Недостаточно данных');
        }
        $order = Order::create([
                'table' => $table,
                'waiter' => $waiter->id,
                'is_pay' => 0
            ]
        );
        foreach ($orderArr as $dish) {
            OrderItem::create([
                'order_id' => $order->id,
                'dish_id' => $dish,
                'cnt' => 1,
            ]);
        }
        Socket::send(['role'=>3,'refresh'=>true]);
    }

    public function getOrdersWaiter(Request $request)
    {
        $id = $request->get('id');
        $role = $request->get('role');
        $ordersBuilder = Order::where('is_pay', '=', '0');
        if ($role == 2) {
            $ordersBuilder->where('waiter', '=', $id);
        }
        $orders = $ordersBuilder
            ->with('orderItem.menu')
            ->orderBy('id', 'DESC')
            ->get();
        return $orders;
    }

    public function orderItemClose(Request $request)
    {
        $id = $request->get('id');
        $orderItem = OrderItem::where('id', '=', $id)
            ->first();
        if (!($orderItem instanceof OrderItem)) {
            return true;
        }
        $orderItem->status = 'close';
        $orderItem->save();

        $order = $orderItem->order;
        $i = 0;
        if ($order instanceof Order) {
            $orderItems = $order->orderItem;
            foreach ($orderItems as $item) {
                if ($item->status == 'close') {
                    $i++;
                }
                if ($i === count($orderItems)) {
                    $order->status = 'close';
                    $order->save();
                }
            }
        }
    }

    public function orderPay(Request $request)
    {
        $id = $request->get('id');
        $order = Order::where('id', '=', $id)
            ->first();
        if ($order instanceof Order) {
            $order->is_pay = 1;
            $order->save();
            return $order;
        }
    }

}
