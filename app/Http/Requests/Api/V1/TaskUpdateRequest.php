<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge([
                'title' => $this->sanitizeTitle($this->title)
            ]);
        }
    }

    /**
     * Sanitize the title input.
     */
    private function sanitizeTitle(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '$1', $value);
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255|min:3',
            'completed' => 'sometimes|boolean',
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
            'completed.boolean' => 'The completed field must be true or false.',
        ];
    }
}
