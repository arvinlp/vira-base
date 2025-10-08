<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanEditRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('plans-edit');
    }

    protected function prepareForValidation()
    {
        // اگر price یا features به صورت JSON string ارسال شده‌اند، دیکد کن
        if ($this->has('price') && is_string($this->price)) {
            $decoded = json_decode($this->price, true);
            $this->merge(['price' => $decoded ?: []]);
        }

        if ($this->has('features') && is_string($this->features)) {
            $decoded = json_decode($this->features, true);
            $this->merge(['features' => $decoded ?: []]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'is_free' => 'sometimes|boolean',
            'is_demo' => 'sometimes|boolean',
            'max_users' => 'nullable|integer|min:0',
            'max_storage_mb' => 'nullable|integer|min:0',

            // مهم: price و features به صورت آرایه
            'price' => 'nullable|array',
            'price.*.label' => 'required_with:price|string|max:255',
            'price.*.amount' => 'required_with:price|numeric|min:0',

            'features' => 'nullable|array',
            'features.*.label' => 'required_with:features|string|max:255',
            'features.*.description' => 'nullable|string|max:1000',
        ];
    }
}
