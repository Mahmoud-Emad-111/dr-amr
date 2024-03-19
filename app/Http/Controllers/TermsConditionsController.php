<?php

namespace App\Http\Controllers;

use App\Models\Terms_Conditions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TermsConditionsController extends Controller
{
    //  function create Terms and Conditions
    public function create_terms_conditions(Request $request)
    {
        $data = Validator::make($request->all(), [
            'text' => 'required',
            'country' => 'required|string',
        ]);

        if ($data->fails()) {
            return response()->json($data->errors(), 400);
        }

        $term = Terms_Conditions::create([
            'text' => $request->text,
            'country' => $request->country,
        ]);

        if ($term) {
            return response('done');
        } else {
            return response()->json(['message' => 'Failed to create term'], 500);
        }
    }
    // find terms and condition by id
    public function getTermById(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required|exists:terms__condition,id',
            'text' => 'required',
            'country' => 'required|string',
        ]);

        $id = $request->id;

        $term = Terms_Conditions::find($id);

        return $term;
    }
    // Update terms and condition

    public function updateTerm(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required|exists:terms__conditions,id',
            'text' => 'required',
            'country' => 'required|string',
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        $id = $request->id;

        $term = Terms_Conditions::find($id);

        $term->update([
            'text' => $request->text,
            'country' => $request->country,
        ]);
        return 'done';
        return $this->handleResponse('', 'The term has been updated successfully');
    }

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

    public function Get_Term(Request $request){
        $data = Validator::make($request->all(), [
            'country' => 'required|exists:terms__conditions,country',
        ]);
        if ($data->fails()) {
            return response()->json($data->errors());
        }
        $data=Terms_Conditions::Where('country',$request->country)->get();
        return $data;
    }


}
