<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Voucher;

class CollectionController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $vouchers = $customer->vouchers
            ->where('expires_at', '>=', now()->format('Y-m-d'))
            ->where('quantity', '>', 0);
        $exchangeVouchers = Voucher::whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->where('points_required', '>', 0)
            ->get();
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id')->toArray();

        $customerPoint = $customer->point;
        if (now()->gt($customerPoint->date_expire)) {
            $customerPoint->update(['total_points' => 0]);
        }

        switch ($customerPoint->ranking_level) {
            case 'Bronze':
                $points = 150;
                $nextLevel = 'Silver';
                $color = '#854C12';
                break;
            case 'Silver':
                $points = 200;
                $nextLevel = 'Gold';
                $color = '#868686';
                break;
            default:
                $points = $customerPoint->total_points;
                $nextLevel = 'Next Level';
                $color = '#FFD700';
                break;
        }

        return view('customer.collection', compact('customerPoint', 'points', 'nextLevel', 'color', 'vouchers', 'customer', 'exchangeVouchers', 'customerVouchers'));
    }
}
