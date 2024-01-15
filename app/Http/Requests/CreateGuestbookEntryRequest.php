<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGuestbookEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'        => 'required|string',
            'content'      => 'required|string',
            'email'        => 'required|email',
            'display_name' => 'required|string',
            'real_name'    => 'required|string',
        ];
    }
}
