<?php

namespace App\Http\Controllers;

use App\Http\Resources\imageResource;
use App\Models\Favirateimage;
use App\Models\Image;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavirateimageController extends Controller
{
    use RandomIDTrait;
    // ================================== New Function Add fav add remove fave exist =============================================
    public function FavirateImage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'image_id' => 'required|integer|exists:images,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $user = auth('sanctum')->user();
        $image_id = $this->getRealID(Image::class, $request->image_id)->id;

        $favoriteImage = Favirateimage::where('user_id', $user->id)
            ->where('image_id', $image_id)
            ->first();

        if ($favoriteImage) {
            $favoriteImage->delete();
            return $this->handelResponse('', 'The image has been removed from your favorites');
        } else {
            Favirateimage::create([
                'user_id' => $user->id,
                'image_id' => $image_id,
            ]);
            return $this->handelResponse('', 'The image has been added to your favorites');
        }
    }

    // ==================================Old Function Add fav only =============================================
    // public  function FavirateImage(Request $request)
    //   {
    //       $validate = Validator::make($request->all(), [
    //           'image_id' => 'required|integer|exists:images,random_id',
    //       ]);

    //       if ($validate->fails()) {
    //           return response()->json($validate->errors());
    //       }
    //       $image_id = $this->getRealID(Image::class, $request->image_id)->id;

    //       $user_id = auth('sanctum')->user()->id;

    //       Favirateimage::create([
    //           'user_id' => $user_id,
    //           'image_id' => $image_id,

    //       ]);
    //       return $this->handelResponse('', 'The image has been added to your favorites');
    //   }

    // ===============================================================================

    public function Get_Favirate_image()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('Favirate_images')->find($user_id)->favirate_images;
        return imageResource::collection($data)->resolve();
    }

    public function deleteFavoriteImage(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'image_id' => 'required|exists:favirateimages,image_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the image ID from the request
        $imageId = $request->image_id;

        // Find and delete the favorite image record
        $favoriteimage = Favirateimage::where('user_id', $user->id)
            ->where('image_id', $imageId)
            ->delete();

        // Check if the image was found and deleted
        if ($favoriteimage) {
            return response()->json(['message' => 'Favorite images deleted successfully']);
        } else {
            return response()->json(['error' => 'Favorite images not found'], 404);
        }
    }
}
