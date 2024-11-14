<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id');
        $vouchers = Voucher::whereDate('expires_at', '>=', Carbon::now())->get();
        return view('customer.voucher', compact('vouchers', 'customerVouchers','customer'));
    }

    public function saveVoucher(Request $request)
    {
        $customer = auth('customer')->user();
        $customerPoint = Point::where('customer_id', $customer->id)->first();
        $voucherId = $request->input('voucher_id');
        $voucher = Voucher::find($voucherId);
        if ($voucher && $voucher->quantity > 0) {
            $pointsRequired = $voucher->points_required;
            if ($customerPoint->total_points < $pointsRequired) {
                return redirect()->route('vouchers')->with('error', 'You do not have enough points to redeem this voucher.');
            }
            $customerPoint->total_points -= $pointsRequired;
            $customerPoint->points_redeemed += $pointsRequired;
            $customerPoint->save();
            $voucher->quantity -= 1;
            $voucher->save();
            $voucher->customers()->attach(auth('customer')->user()->id, ['voucher_id' => $voucherId]);
            return redirect()->route('vouchers')->with('success', 'Voucher saved successfully.');
        }
        return redirect()->route('vouchers')->with('error', 'Voucher could not be saved.');
    }
}
