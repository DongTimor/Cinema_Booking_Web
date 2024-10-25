<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userVouchers = $user->vouchers->where('pivot.status', 0)->pluck('pivot.voucher_id');
        $expiredVouchers = Voucher::whereIn('id', $userVouchers)
            ->where('expires_at', '<', Carbon::now())
            ->pluck('id');
        $allVouchers = Voucher::where(function ($query) use ($user) {
            $query->where('expires_at', '>=', Carbon::now())
              ->whereDoesntHave('users', function ($query) use ($user) {
                  $query->where('user_id', $user->id);
              });
        })->orWhereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id)
              ->where('status', 0);
        })->get();
        $availableVouchers = $allVouchers->filter(function ($voucher) use ($userVouchers) {
            return $userVouchers->contains('id', $voucher->id);
        });
        if ($expiredVouchers->isNotEmpty()) {
            DB::table('user_voucher')
                ->where('user_id', $user->id)
                ->whereIn('voucher_id', $expiredVouchers)
                ->delete();
        }
        $points = Point::where('user_id', Auth::id())->first();
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
        $points = Point::where('user_id', Auth::id())->first();
        return view('customer.voucher', compact('points', 'availableVouchers', 'pointsToNextLevel','allVouchers', 'userVouchers'));
    }

    public function checkAndUpdatePoints()
    {
        $user = Auth::user();
        $point = Point::where('user_id', $user->id)->first();
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
                'message' => 'User points and ranking level updated successfully',
                'data' => $point
            ]);
        } else {
            return response()->json([
                'message' => 'User points record not found'
            ], 404);
        }
    }
}
