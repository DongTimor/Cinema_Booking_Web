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
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id');
        $vouchers = Voucher::whereDate('expires_at', '>=', Carbon::now())->get();
        $points = Point::where('customer_id', $customer->id)->first();
        if ($points) {
            if ($points->ranking_level == 'Silver') {
                $pointsToNextLevel = 200 - $points->total_points;
            } elseif ($points->ranking_level == 'Gold') {
                $pointsToNextLevel = 0; 
            } else {
                $pointsToNextLevel = 150 - $points->total_points;
            }
        } else {
            $pointsToNextLevel = null;
        }
        $this->checkAndUpdatePoints();
        return view('customer.collection', compact('points', 'pointsToNextLevel','vouchers', 'customerVouchers','customer'));
    }

    public function checkAndUpdatePoints()
    {
        $customer = auth('customer')->user();
        $point = Point::where('customer_id', $customer->id)->first();
        if ($point) {
            if ($point->date_expire && Carbon::now()->greaterThan($point->date_expire)) {
                $point->total_points = 0;
            }
            if ($point->total_points > 200) {
                $point->ranking_level = 'Gold';
            } elseif ($point->total_points > 150) {
                $point->ranking_level = 'Silver';
            } else {
                $point->ranking_level = 'Bronze';
            }
            $point->save();
            return response()->json([
                'message' => 'customer points and ranking level updated successfully',
                'data' => $point
            ]);
        } else {
            return response()->json([
                'message' => 'customer points record not found'
            ], 404);
        }
    }
}
