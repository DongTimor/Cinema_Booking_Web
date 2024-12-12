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
        $customerPoint = $customer->point;
        switch ($customerPoint->ranking_level) {
            case 'Bronze':
                $ranks = ['Bronze'];
                break;
            case 'Silver':
                $ranks = ['Silver', 'Bronze'];
                break;
            case 'Gold':
                $ranks = ['Gold', 'Silver', 'Bronze'];
                break;
            default:
                $ranks = [$customerPoint->ranking_level];
                break;
        }
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id');
        $vouchers = Voucher::whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->where('points_required', 0)
            ->where('is_purchasable', 1)
            ->whereIn('rank_required', $ranks)
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

    public function exchangeVoucher()
    {
        $customer = auth('customer')->user();
        $customerPoint = $customer->point;
        switch ($customerPoint->ranking_level) {
            case 'Bronze':
                $ranks = ['Bronze'];
                break;
            case 'Silver':
                $ranks = ['Silver', 'Bronze'];
                break;
            case 'Gold':
                $ranks = ['Gold', 'Silver', 'Bronze'];
                break;
            default:
                $ranks = [$customerPoint->ranking_level];
                break;
        }
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id')->toArray();
        $vouchers = Voucher::whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->where('points_required', '>', 0)
            ->whereIn('rank_required', $ranks)
            ->orderBy('points_required', 'desc')
            ->get();
        return view('home.vouchers.exchange', compact('customer', 'customerPoint', 'customerVouchers', 'vouchers'));
    }

    public function exchange(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        $customer = auth('customer')->user();
        $points = $request->input('points', 0);
        $customerPoints = $customer->point;

        if ($customerPoints->points_earned < $points) {
            return redirect()->route('home.vouchers.exchange')->with('warning', 'You don’t have enough points to exchange this voucher!');
        }

        DB::transaction(function () use ($voucher, $customer, $points, $customerPoints) {
            $voucher->decrement('quantity', 1);
            $customerPoints->decrement('points_earned', $points);
            $customerPoints->increment('points_redeemed', $points);
            $voucher->customers()->attach($customer->id);
        });

        return redirect()->route('home.vouchers.exchange')->with('success', 'The voucher was exchanged successfully!');
    }
}
