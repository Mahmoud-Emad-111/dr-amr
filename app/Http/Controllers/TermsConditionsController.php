<?php

namespace App\Http\Controllers;

use App\Http\Resources\TermsResource;
use App\Models\Terms_Conditions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class TermsConditionsController extends Controller
{
    //  function create Terms and Conditions
    public function create_terms_conditions(Request $request)
    {
        // Determine the language from the request or set a default language
        $language = $request->header('Accept-Language') ?: 'en';

        // Define validation rules and messages based on the language
        $rules = [
            'text' => 'required',
            'country' => 'required|string',
            'text_en' => 'required',
            'country_en' => 'required|string',
        ];

        $messages = [
            'text.required' => $language === 'ar' ? 'حقل النص مطلوب.' : 'The text field is required.',
            'country.required' => $language === 'ar' ? 'حقل البلد مطلوب.' : 'The country field is required.',
            'country.string' => $language === 'ar' ? 'يجب أن يكون البلد نصًا.' : 'The country must be a string.',
            'text_en.required' => $language === 'ar' ? 'حقل النص بالإنجليزية مطلوب.' : 'The English text field is required.',
            'country_en.required' => $language === 'ar' ? 'حقل البلد بالإنجليزية مطلوب.' : 'The English country field is required.',
            'country_en.string' => $language === 'ar' ? 'يجب أن يكون البلد بالإنجليزية نصًا.' : 'The English country must be a string.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $term = Terms_Conditions::create([
            'text' => $request->text,
            'country' => $request->country,
            'text_en' => $request->text_en,
            'country_en' => $request->country_en,
        ]);
        return  TermsResource::make($term)->resolve();
    }
    //==================================================  old fun ==================================================

    // public function create_terms_conditions(Request $request)
    // {
    //     $data = Validator::make($request->all(), [
    //         'text' => 'required',
    //         'country' => 'required|string',
    //     ]);

    //     if ($data->fails()) {
    //         return response()->json($data->errors(), 400);
    //     }

    //     $term = Terms_Conditions::create([
    //         'text' => $request->text,
    //         'country' => $request->country,
    //     ]);

    //     if ($term) {
    //         return response('done');
    //     } else {
    //         return response()->json(['message' => 'Failed to create term'], 500);
    //     }
    // }
    // find terms and condition by id
    public function getTermById(Request $request)
    {
        $language = $request->header('Accept-Language') ?: App::getLocale();

        $data = Validator::make($request->all(), [
            'id' => 'required|exists:terms__conditions,id',
        ]);

        if ($data->fails()) {
            return response()->json($data->errors(), 400);
        }

        $id = $request->id;

        $term = Terms_Conditions::find($id);

        if (!$term) {
            return response()->json(['message' => 'Term not found'], 404);
        }

        // تحديد البيانات المطلوبة بناءً على اللغة
        if ($language === 'ar') {
            return [
                'text' => $term->text,
                'country' => $term->country,
            ];
        } else {
            return [
                'text_en' => $term->text_en,
                'country_en' => $term->country_en,
            ];
        }
    }
    //==================================================  old fun ==================================================

    // public function getTermById(Request $request)
    // {
    //     $data = Validator::make($request->all(), [
    //         'id' => 'required|exists:terms__condition,id',
    //         'text' => 'required',
    //         'country' => 'required|string',
    //     ]);

    //     $id = $request->id;

    //     $term = Terms_Conditions::find($id);

    //     return $term;
    // }
    // Update terms and condition
    public function updateTerm(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required|exists:terms__conditions,id',
            'text' => 'required',
            'country' => 'required|string',
            'text_en' => 'required',
            'country_en' => 'required|string',
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        $id = $request->id;

        $term = Terms_Conditions::find($id);

        $term->update([
            'text' => $request->text,
            'country' => $request->country,
            'text_en' => $request->text_en,
            'country_en' => $request->country_en,
        ]);
        return  TermsResource::make($term)->resolve();

        return $this->handleResponse('', 'The term has been updated successfully');
    }
    //==================================================  old fun ==================================================

    // public function updateTerm(Request $request)
    // {
    //     $data = Validator::make($request->all(), [
    //         'id' => 'required|exists:terms__conditions,id',
    //         'text' => 'required',
    //         'country' => 'required|string',
    //     ]);

    //     if ($data->fails()) {
    //         return response()->json($data->errors());
    //     }

    //     $id = $request->id;

    //     $term = Terms_Conditions::find($id);

    //     $term->update([
    //         'text' => $request->text,
    //         'country' => $request->country,
    //     ]);
    //     return 'done';
    //     return $this->handleResponse('', 'The term has been updated successfully');
    // }
    // function delete terms
    public function deleteTerm(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required|exists:terms__conditions,id',
        ]);
        if ($data->fails()) {
            return response()->json($data->errors());
        }

        $id = $request->id;

        $term = Terms_Conditions::find($id);

        $term->delete();

        return response('Terms and conditions have been deleted');
    }
    // func Get Term
    public function Get_Term(Request $request)
    {
        $data = Validator::make($request->all(), [
            'country' => 'required|exists:terms__conditions,country',
        ]);
        if ($data->fails()) {
            return response()->json($data->errors());
        }
        $data = Terms_Conditions::Where('country', $request->country)->get();
        return $data;
    }
}
