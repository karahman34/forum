<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        if ($this->isMethod('POST')) {
            return [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                'tags' => 'required|array',
                'tags.*' => 'string|max:255',
                'images' => 'array',
                'images.*' => 'image|mimes:png,jpg,jpeg|max:4096'
            ];
        } else {
            return [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                'tags' => 'required|array',
                'tags.*' => 'string|max:255',
                'images' => 'array',
                'images.*' => 'image|mimes:png,jpg,jpeg|max:4096',
                'old_images' => 'array',
                'old_images.*' => 'string|max:255',
            ];
        }
    }
}
