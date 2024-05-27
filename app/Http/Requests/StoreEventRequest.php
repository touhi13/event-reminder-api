<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'event_date'            => ['required', 'date', 'after:now'],
            'reminder_recipients'   => 'nullable|array',
            'reminder_recipients.*' => 'email',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'event_date.after' => 'The event date must be a future date.',
        ];
    }
}
