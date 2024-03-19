<?php

namespace App\Http\Requests\Audios;

use Illuminate\Foundation\Http\FormRequest;

class AudioUpdateRequest extends FormRequest
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
            'id'=>'required|integer|exists:audios,random_id',
            'image' => 'required|image|max:2048',
            'audio' => 'required|mimes:mp3,wav',
            'status' => 'required|in:public,private',
            'title' => 'required',
            'Audio_category'=>'required|integer|exists:audios_categories,random_id'
        ];
    }
}
