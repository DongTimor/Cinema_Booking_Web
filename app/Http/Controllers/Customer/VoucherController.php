<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id');
        $vouchers = Voucher::whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->get();
        return view('customer.voucher', compact('customer', 'customerVouchers', 'vouchers'));
    }

    public function save($id)
    {
        $voucher = Voucher::find($id);
        $customer = auth('customer')->user();

        DB::transaction(function () use ($voucher, $customer) {
            $voucher->decrement('quantity', 1);
            $voucher->customers()->attach($customer->id);
        });

        return redirect()->route('vouchers')->with('success', 'Voucher saved successfully!');
    }


    public function exchange(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        $customer = auth('customer')->user();
        $points = $request->input('points', 0);
        $customerPoints = $customer->point;

        if ($customerPoints->points_earned < $points) {
            return redirect()->route('vouchers')->with('error', 'You do not have enough points to redeem this voucher!');
        }

        DB::transaction(function () use ($voucher, $customer, $points, $customerPoints) {
            $voucher->decrement('quantity', 1);
            $customerPoints->decrement('points_earned', $points);
            $customerPoints->increment('points_redeemed', $points);
            $voucher->customers()->attach($customer->id);
        });

        return redirect()->route('vouchers')->with('success', 'Points exchanged successfully!');
    }
}
