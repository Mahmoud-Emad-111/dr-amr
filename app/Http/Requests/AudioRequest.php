<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AudioRequest extends FormRequest
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
            'title' => 'required',
            'image' =>  'required|image',
            'audio' => 'required|mimes:mp3,wav',
            'status' => 'required|in:Public,Private',
            'elder_id'=>'required|integer|exists:elders,random_id',
            'Audio_category'=>'required|integer|exists:audios_categories,random_id',
        ];
    }
}
