<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotspotRequest extends FormRequest
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
            'scene_id' => 'required|exists:scenes,id',
            'type' => 'required|in:info,scene',
            'pitch' => 'required|numeric|between:-90,90',
            'yaw' => 'required|numeric|between:0,360',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:1000',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'target_scene_id' => 'nullable|exists:scenes,id|different:scene_id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'scene_id.required' => __('validation.scene_required'),
            'scene_id.exists' => __('validation.scene_not_found'),
            'type.required' => __('validation.type_required'),
            'type.in' => __('validation.type_invalid'),
            'pitch.required' => __('validation.pitch_required'),
            'pitch.between' => __('validation.pitch_range'),
            'yaw.required' => __('validation.yaw_required'),
            'yaw.between' => __('validation.yaw_range'),
            'title.required' => __('validation.title_required'),
            'image_path.image' => __('validation.image_format'),
            'image_path.max' => __('validation.image_max_size', ['size' => '5MB']),
            'target_scene_id.exists' => __('validation.target_scene_not_found'),
            'target_scene_id.different' => __('validation.target_scene_different'),
        ];
    }
}
