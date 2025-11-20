<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Note: email is intentionally not editable server-side.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{9,15}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            // email not present or ignored if submitted
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge(['phone' => preg_replace('/[^0-9]/', '', $this->input('phone'))]);
        }
    }
}
