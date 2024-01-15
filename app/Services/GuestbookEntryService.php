<?php

namespace App\Services;

use App\Events\DeleteGuestBookEntryEvent;
use App\Exceptions\BadResquestException;
use App\Models\GuestbookEntry;
use App\Models\Submitter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuestbookEntryService
{
    /**
     * Create Guestbook entry with provided data. Creates submitter if it does not exist
     * @param array $data
     * @return GuestbookEntry|null
     * @throws Exception
     */
    public function createGuestbookEntry(array $data): ?GuestbookEntry
    {
        DB::beginTransaction();

        try {
            // Now get or create the submitter if not exists
            /** @var Submitter $submitter */
            $submitter = Submitter::firstOrCreate(
                ["email" => $data['email']],
                ["real_name" => $data['real_name'], "display_name" => $data['display_name']]
            );

            $data['submitter_id'] = $submitter->id;
            /** @var GuestbookEntry $guestBookEntry */
            $guestBookEntry = GuestbookEntry::create($data);
        } catch (Exception $e) {
            DB::rollBack();

            // generate error log to default logger
            Log::error($e);

            throw $e;
        }

        DB::commit();

        return $guestBookEntry;
    }

    /**
     * Updates guestbook entry by provided data
     * @param GuestbookEntry $guestbookEntry
     * @param array $data
     * @return GuestbookEntry
     * @throws Exception
     */
    public function update(GuestbookEntry $guestbookEntry, array $data): GuestbookEntry
    {
        DB::beginTransaction();

        try {
            //check if data is empty, in case it reached here empty
            if (empty($data)) {
                throw new BadResquestException('No data received to update!');
            }

            if (!$guestbookEntry->update($data)) {
                throw new Exception('There was a problem updating the guestbook entry!');
            }
        } catch (Exception $e) {
            DB::rollBack();

            Log::error($e);

            throw $e;
        }

        DB::commit();

        return $guestbookEntry;
    }

    /**
     * Gets guestbook entries by user email
     * @param string $userEmail
     * @return Collection
     */
    public function getUserEntriesByEmail(string $userEmail): Collection
    {
        return GuestbookEntry::whereHas('submitter', function (Builder $query) use ($userEmail) {
            $query->where('email', $userEmail);
        })->get();
    }

    /**
     * Deletes given guestbook entry
     * @param GuestbookEntry $entry
     * @return void
     */
    public function delete(GuestbookEntry $entry): void
    {
        if($entry->delete()) {
            event(new DeleteGuestBookEntryEvent($entry->submitter->email));
        }

        // if something goes wrong, an exception should be thrown by the model operation
    }

    public function notifyUserOfDeletion(String $email)
    {
        // Logic Here
    }

    public function generateNewReport()
    {
        // Logic Here
    }

    public function performCleanupTasks()
    {
        // Logic Here
    }
}
