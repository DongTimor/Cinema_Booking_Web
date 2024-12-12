<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
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
            'value' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type == 'percent' && ($value < 0 || $value > 100)) {
                        $fail('The ' . $attribute . ' must be between 0 and 100 when type is percent.');
                    }
                },
            ],
            'points_required' => 'integer|min:0',
            'rank_required' => 'required',
            'expires_at' => 'required',
        ]);

        Voucher::create($request->all());
        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully!');
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
            'value' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type == 'percent' && ($value < 0 || $value > 100)) {
                        $fail('The ' . $attribute . ' must be between 0 and 100 when type is percent.');
                    }
                },
            ],
            'points_required' => 'integer|min:0',
            'rank_required' => 'required',
            'expires_at' => 'required',
        ]);

        $voucher = Voucher::find($id);
        $voucher->update($request->all());
        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully!');
    }

    public function destroy(string $id)
    {
        Voucher::destroy($id);
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}
