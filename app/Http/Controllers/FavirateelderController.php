<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetDonwloadElderResources;
use App\Models\Elder;
use App\Models\Favirateelder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\RandomIDTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class FavirateelderController extends Controller
{
    use RandomIDTrait;

    public function favirateElder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'elder_id' => 'required|integer|exists:elders,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $user = auth('sanctum')->user();
        $elder_id = $this->getRealID(Elder::class, $request->elder_id)->id;

        $favoriteElder = Favirateelder::where('user_id', $user->id)
            ->where('elder_id', $elder_id)
            ->first();

        if ($favoriteElder) {
            $favoriteElder->delete();
            return $this->handelResponse('', 'The elder has been removed from your favorites');
        } else {
            Favirateelder::create([
                'user_id' => $user->id,
                'elder_id' => $elder_id,
            ]);
            return $this->handelResponse('', 'The elder has been added to your favorites');
        }
    }


    public function Get_Favirate_Elder()
    {
        $user_id = auth('sanctum')->user()->id;
        $elder = User::with('Favirate_Elder')->find($user_id)->favirate_elder;
        return GetDonwloadElderResources::collection($elder)->resolve();
    }

    public function deleteFavoriteElder(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'elder_id' => 'required|exists:favirateelders,elder_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the elder ID from the request
        $elderId = $request->elder_id;

        // Find and delete the favorite elder record
        $favoriteelder = Favirateelder::where('user_id', $user->id)
            ->where('elder_id', $elderId)
            ->delete();

        // Check if the elder was found and deleted
        if ($favoriteelder) {
            return response()->json(['message' => 'Favorite elders deleted successfully']);
        } else {
            return response()->json(['error' => 'Favorite elders not found'], 404);
        }
    }
}
