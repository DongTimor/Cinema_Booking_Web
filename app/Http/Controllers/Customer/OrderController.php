<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = auth('customer')->user();
        $orders = $customer->orders;
        // dd($orders);
        return view('customer.orders.index', compact('customer','orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = auth('customer')->user();
        $order = Order::findOrFail($id);
        return view('customer.orders.index', compact('customer','order'));
    }
}
