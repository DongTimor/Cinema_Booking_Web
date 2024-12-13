<?php

namespace App\Console\Commands;

use App\Mail\BirthdayNotification;
use App\Models\Customer;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckBirthdays extends Command
{
    protected $signature = 'birthday:check';
    protected $description = 'Check for customers with birthdays today and send notifications';

    public function handle()
    {
        $today = today();
        $customers = Customer::whereDay('birth_date', $today->day)->whereMonth('birth_date', $today->month)->get();
        
        if ($customers->isEmpty()) {
            $this->info('No customers with birthdays today.');
            return;
        }

        foreach ($customers as $customer) {
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
            Mail::to($customer->email)->send(new BirthdayNotification($customer));
        }
        $this->info('Birthday notifications sent to customers.');
    }
}