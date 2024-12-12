<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Voucher;

use Carbon\Carbon;
use Illuminate\Support\Str;


class CollectionController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $customerPoint = $customer->point;
        if (now()->gt($customerPoint->date_expire)) {
            $customerPoint->update(['total_points' => 0]);
        }

        switch ($customerPoint->ranking_level) {
            case 'Bronze':
                $points = 1000;
                $nextLevel = 'Silver';
                $color = '#854C12';
                break;
            case 'Silver':
                $points = 3000;
                $nextLevel = 'Gold';
                $color = '#868686';
                break;
            default:
                $points = $customerPoint->total_points;
                $nextLevel = 'Next Level';
                $color = '#FFD700';
                break;
        }
        if ($customer->birth_date && Carbon::parse($customer->birth_date)->format('m-d') == now()->format('m-d')) {
            $birthdayVoucher = Voucher::where('description', 'Birthday')
            ->whereHas('customers', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->first();

            if (!$birthdayVoucher) {
            $voucher = new Voucher();
            $voucher->code = Str::random(6); 
            $voucher->description = 'Birthday';
            $voucher->quantity = 1;
            $voucher->value = 20; 
            $voucher->type = 'percent';
            $voucher->points_required = 0;
            $voucher->is_purchasable = 0; 
            $voucher->rank_required = 'Bronze';
            $voucher->expires_at = Carbon::now()->addDays(7);
            $voucher->save();
            $voucher->customers()->attach($customer->id);
            }
        }
        $vouchers = $customer->vouchers
            ->where('expires_at', '>=', now()->format('Y-m-d'))
            ->where('quantity', '>', 0);
        $exchangeVouchers = Voucher::whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->where('points_required', '>', 0)
            ->get();
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id')->toArray();
        return view('customer.collection', compact('customerPoint', 'points', 'nextLevel', 'color', 'vouchers', 'customer', 'exchangeVouchers', 'customerVouchers'));
    }
}
