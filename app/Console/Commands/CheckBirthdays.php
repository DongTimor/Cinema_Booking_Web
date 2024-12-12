<?php

namespace App\Console\Commands;

use App\Mail\BirthdayNotification;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckBirthdays extends Command
{
    protected $signature = 'birthday:check';
    protected $description = 'Check for customers with birthdays today and send notifications';

    public function handle()
    {
        $today = Carbon::now()->format('m-d');
        $customers = Customer::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$today])->get();
        
        if ($customers->isEmpty()) {
            $this->info('No customers with birthdays today.');
            return;
        }

        foreach ($customers as $customer) {
            Mail::to($customer->email)->send(new BirthdayNotification($customer));
        }
        $this->info('Birthday notifications sent to customers.');
    }
}