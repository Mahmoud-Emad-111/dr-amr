<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudiofavirateUserResource;
use App\Models\Audio;
use App\Models\DownloadAudio;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DownloadAudioController extends Controller
{
    //    ############ Download audio from user ##################
    use RandomIDTrait;
    public  function Donwload(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'audio_id' => 'required|integer|exists:audios,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $audio_id = $this->getRealID(Audio::class, $request->audio_id)->id;

        $user_id = auth('sanctum')->user()->id;

        DownloadAudio::create([
            'user_id' => $user_id,
            'audio_id' => $audio_id,

        ]);
        return asset(Storage::url(Audio::find($audio_id)->audio));
    }

    public function getAudioData()
    {
        $user_id = auth('sanctum')->user()->id;
        $data= User::with('Audio')->find($user_id);
        return AudiofavirateUserResource::collection($data->audio)->resolve();

    }
}
