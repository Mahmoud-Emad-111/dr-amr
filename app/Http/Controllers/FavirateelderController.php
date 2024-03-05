<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetDonwloadElderResources;
use App\Models\Elder;
use App\Models\Favirateelder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\RandomIDTrait;
use Illuminate\Support\Facades\Validator;


class FavirateelderController extends Controller
{
    use RandomIDTrait;

    public  function favirateElder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'elder_id' => 'required|integer|exists:elders,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $elder_id = $this->getRealID(Elder::class, $request->elder_id)->id;

        $user_id = auth('sanctum')->user()->id;

        Favirateelder::create([
            'user_id' => $user_id,
            'elder_id' => $elder_id,

        ]);
        return $this->handelResponse('','The elder has been added to your favorites');
    }

    public function Get_Favirate_Elder()
    {
        $user_id = auth('sanctum')->user()->id;
          $elder = User::with('Favirate_Elder')->find($user_id)->favirate_elder;
         return GetDonwloadElderResources::collection($elder)->resolve();

    }

}
