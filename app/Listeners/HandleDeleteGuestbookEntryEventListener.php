<?php

namespace App\Listeners;

use App\Events\DeleteGuestBookEntryEvent;
use App\Services\GuestbookEntryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleDeleteGuestbookEntryEventListener
{
    /**
     * @var GuestbookEntryService $guestbookEntryService
     */
    private GuestbookEntryService $guestbookEntryService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GuestbookEntryService $service)
    {
        $this->guestbookEntryService = $service;
    }

    /**
     * Handle the event.
     *
     * @param  DeleteGuestBookEntryEvent  $event
     * @return void
     */
    public function handle(DeleteGuestBookEntryEvent $event): void
    {
        $this->guestbookEntryService->performCleanupTasks();
        $this->guestbookEntryService->generateNewReport();
        $this->guestbookEntryService->notifyUserOfDeletion($event->email);
    }
}
