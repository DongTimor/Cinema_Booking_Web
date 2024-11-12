<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\Voucher;
use Carbon\Carbon;

class CollectionController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $vouchers = $customer->vouchers
            ->where('expires_at', '>=', now()->format('Y-m-d'))
            ->where('quantity', '>', 0);
        $customerPoint = Point::where('customer_id', $customer->id)->first();

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

        return view('customer.collection', compact('customerPoint', 'points', 'nextLevel', 'color', 'vouchers', 'customer'));
    }
}
