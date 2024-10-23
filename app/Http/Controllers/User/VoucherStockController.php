<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Voucher;

class VoucherStockController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        dd($customer);
        $voucherStock = Voucher::select('*')
        ->join('customer_voucher', 'vouchers.id', '=', 'customer_voucher.voucher_id')
        ->where('customer_voucher.customer_id', auth()->user()->customer->id)
        ->get();
        return view('user.voucher-stocks.index', compact('voucherStock'));
    }
}
