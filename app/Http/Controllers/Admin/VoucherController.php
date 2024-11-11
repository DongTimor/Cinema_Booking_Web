<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{

    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $code = Str::random(6);
        return view('admin.vouchers.create', compact('code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|min:6|max:6|unique:vouchers,code',
            'description' => 'required|max:255',
            'quantity' => 'required|integer|max:100',
            'value' => 'required|integer',
            'expires_at' => 'required',
        ]);
        $code = $request->all();
        Voucher::create($code);
        return redirect()->route('vouchers.index');
    }

    public function edit(string $id)
    {
        $voucher = Voucher::find($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'description' => 'required|max:255',
            'quantity' => 'required|integer|max:100',
            'value' => 'required|integer',
            'expires_at' => 'required',
        ]);
        $voucher = Voucher::find($id);
        $voucher->update($request->all());
        return redirect()->route('vouchers.index');
    }

    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);
        $voucher->delete();
        return redirect()->route('vouchers.index');
    }

    public function getVoucherOfCustomer(string $id)
    {
        try {
            $voucher = Customer::findOrFail($id)->vouchers()->where('status', '0')->get();
            return response()->json($voucher);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getVoucherInfo(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return response()->json($voucher);
    }
}
