<?php

namespace App\Http\Requests;

use App\Models\GuestbookEntry;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateGuestbookEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var GuestbookEntry $guestbookEntry */
        $guestbookEntry = $this->route('entry');

        /** @var User $user */
        $user = Auth::user();

        return $guestbookEntry->submitter->email === $user->email;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'   => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
        ];
    }
}
