<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            //
            'facebook'=>'url',
            'whatsapp'=>'url',
            'messenger'=>'url',
            'instagram'=>'url',
            'image'=>'image',
            'logo'=>'image',
            'prayer_timings'=>'in:0,1',
            'code_private'=>'numeric',
            'app_store'=>'url',
            'play_store'=>'url',
        ]
        ;
    }
}
