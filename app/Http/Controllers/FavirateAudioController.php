<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudiofavirateUserResource;
use App\Models\Audio;
use App\Models\Favirateaudios;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavirateAudioController extends Controller
{
    use RandomIDTrait;

    public function FavirateAudio(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'audio_id' => 'required|integer|exists:audios,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $user = auth('sanctum')->user();
        $audio_id = $this->getRealID(Audio::class, $request->audio_id)->id;

        $favoriteAudio = Favirateaudios::where('user_id', $user->id)
            ->where('audio_id', $audio_id)
            ->first();

        if ($favoriteAudio) {
            $favoriteAudio->delete();
            return $this->handelResponse('', 'The Audio has been removed from your favorites');
        } else {
            Favirateaudios::create([
                'user_id' => $user->id,
                'audio_id' => $audio_id,
            ]);
            return $this->handelResponse('', 'The Audio has been added to your favorites');
        }
    }

    public function Get_Favirate_Audios()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('Favirate_Audios')->find($user_id)->favirate_audios;
        return AudiofavirateUserResource::collection($data)->resolve();
    }

    public function deleteFavoriteSong(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'audio_id' => 'required|exists:favirateaudios,audio_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the audio ID from the request
        $audioId = $request->audio_id;

        // Find and delete the favorite song record
        $favoriteSong = Favirateaudios::where('user_id', $user->id)
            ->where('audio_id', $audioId)
            ->delete();

        // Check if the song was found and deleted
        if ($favoriteSong) {
            return response()->json(['message' => 'Favorite song deleted successfully']);
        } else {
            return response()->json(['error' => 'Favorite song not found'], 404);
        }
    }
}   
