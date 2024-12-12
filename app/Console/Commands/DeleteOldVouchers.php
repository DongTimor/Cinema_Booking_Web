<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;
use Carbon\Carbon;

class DeleteOldVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vouchers:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete vouchers that are older than 365 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(364);
        $oldVouchers = Voucher::where('created_at', '<', $date)->get();

        foreach ($oldVouchers as $voucher) {
            $voucher->delete();
        }

        $this->info('Old vouchers deleted successfully.');
        return 0;
    }
}