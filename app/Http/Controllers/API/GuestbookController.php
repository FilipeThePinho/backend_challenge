<?php

namespace App\Http\Controllers\API;

use App\Exceptions\BadResquestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGuestbookEntryRequest;
use App\Http\Requests\UpdateGuestbookEntryRequest;
use App\Models\GuestbookEntry;
use App\Services\GuestbookEntryService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GuestbookController extends Controller
{

    private GuestbookEntryService $guestbookEntryService;

    public function __construct(GuestbookEntryService $guestbookEntryService)
    {
        $this->guestbookEntryService = $guestbookEntryService;
    }

    /**
     * Show all the guestbook entries.
     * @return Collection
     */
    public function index(): Collection
    {
        return GuestbookEntry::all();
    }

    /**
     * Show all the guestbook entries for a given submitter email.
     * @param Request $request
     * @return Collection|Response|ResponseFactory
     */
    public function my(Request $request): Collection|Response|ResponseFactory
    {
        if ($request->user() === null)
            return response("Not logged in", 401);

        return $this->guestbookEntryService->getUserEntriesByEmail($request->user()?->email);
    }

    /**
     * Show selected guestbook entries.
     * @param GuestbookEntry $entry
     * @return GuestbookEntry
     */
    public function get(GuestbookEntry $entry): GuestbookEntry
    {
        return $entry;
    }

    /**
     * Delete selected guestbook entries.
     * @param GuestbookEntry $entry
     * @return Response
     */
    public function delete(GuestbookEntry $entry): Response
    {
        $this->guestbookEntryService->delete($entry);

        return response("Entry successfully Deleted");
    }

    /**
     * Create new guestbook entries based on request
     * @param CreateGuestbookEntryRequest $request
     * @return GuestbookEntry
     * @throws Exception
     */
    public function sign(CreateGuestbookEntryRequest $request): GuestbookEntry
    {
        return $this->guestbookEntryService->createGuestbookEntry($request->validated());
    }

    /**
     * Update the given guestbook entry based on request
     * @param GuestbookEntry $entry
     * @param UpdateGuestbookEntryRequest $request
     * @return GuestbookEntry|Response
     * @throws Exception
     */
    public function update(GuestbookEntry $entry, UpdateGuestbookEntryRequest $request): GuestbookEntry|Response
    {
        try {
            return $this->guestbookEntryService->update($entry, $request->validated());
        } catch (BadResquestException $e) {
            return response($e->getMessage(), 400);
        }
    }
}
