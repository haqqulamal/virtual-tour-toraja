<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArtifactRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'title_id' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_id' => 'required|string|max:2000',
            'description_en' => 'required|string|max:2000',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB
            'material' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => __('validation.category_required'),
            'category_id.exists' => __('validation.category_not_found'),
            'title_id.required' => __('validation.title_required'),
            'title_en.required' => __('validation.title_required'),
            'description_id.required' => __('validation.description_required'),
            'description_en.required' => __('validation.description_required'),
            'image_path.image' => __('validation.image_format'),
            'image_path.max' => __('validation.image_max_size', ['size' => '10MB']),
        ];
    }
}
