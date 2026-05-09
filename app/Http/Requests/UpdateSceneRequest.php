<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSceneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // 50MB
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => __('validation.title_required'),
            'description.required' => __('validation.description_required'),
            'image_path.image' => __('validation.image_format'),
            'image_path.max' => __('validation.image_max_size', ['size' => '50MB']),
            'thumbnail.image' => __('validation.image_format'),
            'thumbnail.max' => __('validation.thumbnail_max_size', ['size' => '5MB']),
            'order.required' => __('validation.order_required'),
        ];
    }
}
