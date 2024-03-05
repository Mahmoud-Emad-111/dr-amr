<?php

namespace App\Http\Controllers;

use App\Http\Resources\imageResource;
use App\Models\DownloadImage;
use App\Models\Image;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
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
        return asset(Storage::url(Image::find($image_id)->image));
    }
    public function getImage()
    {
        $user_id = auth('sanctum')->user()->id;
       $data = User::with('image')->find($user_id);
       return imageResource::collection($data->image)->resolve();

    }
}
