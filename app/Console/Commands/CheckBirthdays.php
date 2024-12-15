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
            $customer->vouchers()->firstOrCreate([
                'description' => 'Birthday',
            ], [
                'code' => Str::random(6),
                'quantity' => 1,
                'value' => 20,
                'type' => 'percent',
                'points_required' => 0,
                'is_purchasable' => 0,
                'rank_required' => 'Bronze',
                'expires_at' => Carbon::now()->addDays(7),
            ]);
            Mail::to($customer->email)->send(new BirthdayNotification($customer));
        }
        $this->info('Birthday notifications sent to customers.');
    }
}