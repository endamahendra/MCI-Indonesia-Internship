<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class DailyTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the daily task';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $cutoffDate = Carbon::now()->subDays(14);
        $affectedRows = Order::where('tanggal_pengiriman', '<', $cutoffDate)
                             ->where('status', 'dikirim')
                             ->update([
                                 'status' => 'diterima',
                                 'tanggal_diterima' => Carbon::now()
                             ]);

        $completedRows = Order::where('status', 'diterima')
                              ->where('tanggal_diterima', '<', $cutoffDate)
                              ->update(['status' => 'selesai']);

    }
}
