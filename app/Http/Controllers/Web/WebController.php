<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGuestbookEntryRequest;
use App\Models\GuestbookEntry;
use App\Services\GuestbookEntryService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WebController extends Controller
{

    private GuestbookEntryService $guestbookEntryService;

    public function __construct(GuestbookEntryService $guestbookEntryService)
    {
        $this->guestbookEntryService = $guestbookEntryService;
    }

    /**
     * Show all the guestbook entries with a view.
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        $entries = GuestbookEntry::all();
        return view('index', ["entries" => $entries]);
    }

    /**
     * Show all the guestbook entries for a given submitter email.
     * @return View|Factory|RedirectResponse
     */
    public function form(): View|Factory|RedirectResponse
    {
        return view('form');
    }

    public function create(CreateGuestbookEntryRequest $request): View|Factory|RedirectResponse
    {
        try {
            $this->guestbookEntryService->createGuestbookEntry($request->validated());
        } catch (Exception $e) {
            // Do not expose $e as it may contain infrastructure info
            return redirect()->route('submit')->withErrors([
                'error' => 'There was a problem creating an entry. Please try again later or contact support.',
            ]);

        }

        return view('form')->with('status', 'Guestbook Entry successfully created');
    }

}
