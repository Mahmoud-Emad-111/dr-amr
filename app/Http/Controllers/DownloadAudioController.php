<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudiofavirateUserResource;
use App\Models\Audio;
use App\Models\DownloadAudio;
use App\Models\DownloadBook;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return response()->json([
            'audio_url' => asset(Storage::url(Audio::find($audio_id)->audio)),
            'Massage' => 'The Audio has been Downloaded successfully',
        ]);

        // return asset(Storage::url(Audio::find($audio_id)->audio));
    }

    public function getAudioData()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('Audio')->find($user_id);
        return AudiofavirateUserResource::collection($data->audio)->resolve();
    }

    public function deleteDownloadAudio(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'audio_id' => 'required|exists:download_audio,audio_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the audio ID from the request
        $audioId = $request->audio_id;

        // Find and delete the Downloade audio record
        $Downloadaudio = DownloadAudio::where('user_id', $user->id)
            ->where('audio_id', $audioId)
            ->delete();

        // Check if the audio was found and deleted
        if ($Downloadaudio) {
            return response()->json(['message' => 'Downloade audios deleted successfully']);
        } else {
            return response()->json(['error' => 'Downloade audios not found'], 404);
        }
    }
}
