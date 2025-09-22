<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => $this->sanitizeTitle($this->title)
        ]);
    }

    /**
     * Sanitize the title input.
     */
    private function sanitizeTitle(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        // Remove tags HTML/XML
        $value = strip_tags($value);

        // Remove caracteres de controle
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);

        // Trim espaços extras
        $value = trim($value);

        // Remove múltiplos espaços
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.max' => 'The title must not be greater than 255 characters.',
            'title.string' => 'The title must be a string.',
            'title.min' => 'The title must be at least 3 characters long.',
        ];
    }
}
