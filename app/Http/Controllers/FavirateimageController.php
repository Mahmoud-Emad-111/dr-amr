<?php

namespace App\Http\Controllers;

use App\Http\Resources\imageResource;
use App\Models\Favirateimage;
use App\Models\Image;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavirateimageController extends Controller
{
    use RandomIDTrait;
    public  function FavirateImage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'image_id' => 'required|integer|exists:images,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $image_id = $this->getRealID(Image::class, $request->image_id)->id;

        $user_id = auth('sanctum')->user()->id;

        Favirateimage::create([
            'user_id' => $user_id,
            'image_id' => $image_id,

        ]);
        return $this->handelResponse('','The image has been added to your favorites');
    }
    public function Get_Favirate_image(){
         $user_id = auth('sanctum')->user()->id;
        $data = User::with('Favirate_images')->find($user_id)->favirate_images;
        return imageResource::collection($data)->resolve();

    }
}
