<?php

namespace App\Http\Controllers;

use App\Http\Resources\All_Audios_CategoriesResources;
use App\Http\Resources\DonwloadElderResources;
use App\Http\Resources\ElderResource;
use App\Http\Resources\GetDonwloadElderResources;
use App\Models\Downloadelder;
use App\Models\Elder;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DownloadelderController extends Controller
{
    use RandomIDTrait;

    public  function DownloadElder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'elder_id' => 'required|integer|exists:elders,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $elder_id = $this->getRealID(Elder::class, $request->elder_id)->id;

        $user_id = auth('sanctum')->user()->id;

        Downloadelder::create([
            'user_id' => $user_id,
            'elder_id' => $elder_id,
        ]);

        return DonwloadElderResources::collection(Elder::with('Audio')->find($elder_id)->audio)->resolve();
        ;
    }
    public function getElder()
    {
        $user_id = auth('sanctum')->user()->id;
         $elder = User::with('elder')->find($user_id)->elder;
         return GetDonwloadElderResources::collection($elder)->resolve();

    }
}
