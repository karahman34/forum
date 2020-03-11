<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'body' => 'required|string|max:4294967295',
            'images' => 'array',
            'images.*' => 'image|mimes:png,jpg,jpeg,gif|max:4096',
        ];

        if ($this->isMethod('POST')) {
            return $rules;
        } else {
            $rules['old_images'] = 'array';
            $rules['old_images.*'] = 'image|mimes:png,jpg,jpeg,gif|max:4096';

            return $rules;
        }
    }
}
