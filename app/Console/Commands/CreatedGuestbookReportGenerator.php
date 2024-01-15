<?php

namespace App\Console\Commands;

use App\Models\GuestbookEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreatedGuestbookReportGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:guestbook_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate report of the new created Entries';

    /**
     * Set command max time (in seconds)
     *
     * @var int
     */
    public int $timeout = 1800;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Initialise Carbon instance with now() and subtract 1 hour
        $reportMinCreateDate = now()->subHours(1);
        $guestbookEntries    = GuestbookEntry::where('created_at', '>=', $reportMinCreateDate)->get();

        Storage::disk('local')->put('/reports/guestbook_'. $reportMinCreateDate->toDateTimeString() . '.txt', $guestbookEntries->toJson());

        return Command::SUCCESS;
    }
}
