<?php

namespace App\Http\Controllers;

use App\Http\Resources\imageResource;
use App\Models\DownloadImage;
use App\Models\Image;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DownloadImageController extends Controller
{
    //    ############ Download audio from user ##################
    use RandomIDTrait;
    public  function DownloadImage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'image_id' => 'required|integer|exists:images,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $image_id = $this->getRealID(Image::class, $request->image_id)->id;

        $user_id = auth('sanctum')->user()->id;

        DownloadImage::create([
            'user_id' => $user_id,
            'image_id' => $image_id,

        ]);
        return response()->json([
            'Image_url' => asset(Storage::url(Image::find($image_id)->image)),

            'Massage' => 'The Image has been Downloaded successfully',
        ]);
        // return asset(Storage::url(Image::find($image_id)->image));
    }

    public function getImage()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('image')->find($user_id);
        return imageResource::collection($data->image)->resolve();
    }

    public function deleteDownloadImage(Request $request)
    {
        $user = Auth::user();

        $data = Validator::make($request->all(), [
            'image_id' => 'required|exists:download_images,image_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the image ID from the request
        $imageId = $request->image_id;

        // Find and delete the download image record
        $downloadimage = DownloadImage::where('user_id', $user->id)
            ->where('image_id', $imageId)
            ->delete();

        // Check if the image was found and deleted
        if ($downloadimage) {
            return response()->json(['message' => 'download images deleted successfully']);
        } else {
            return response()->json(['error' => 'download images not found'], 404);
        }
    }
}
