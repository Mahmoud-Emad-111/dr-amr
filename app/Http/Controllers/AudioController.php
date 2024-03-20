<?php

namespace App\Http\Controllers;

use App\Http\Requests\AudioRequest;
use App\Http\Resources\AudioAllElderResource;
use App\Http\Resources\AudioPublicResource;
use App\Http\Resources\AudioResource;
use App\Http\Resources\MostListenedsource;
use App\Models\Audio;
use App\Models\AudiosCategories;
use App\Models\Elder;
use App\Models\Favirateaudios;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// use App\Models\Elder;

class AudioController extends Controller
{

    use SaveImagesTrait, RandomIDTrait,SendNotification;
    public function store(AudioRequest $request)
    {

        $elder_id = $this->getRealID(Elder::class, $request->elder_id)->id;
        $category_id = $this->getRealID(AudiosCategories::class, $request->Audio_category)->id;
        //   handle create image
        $image = $this->saveImage($request->file('image'), 'Audio_image');
        // handle file create
        $audio = $request->file('audio')->store('Audio', 'dr_amr');
        // Get Validated Audio Data

        $item=Audio::create([
            'title' => $request->title,
            'image' => $image,
            'audio' => $audio,
            'status' => $request->status,
            'elder_id' => $elder_id,
            'tag_name' => $request->tag_name,
            'audios_categories_id' => $category_id,
            'random_id' => $this->RandomID(),
            'visits_count' => 0, // تعيين قيمة الزيارات الافتراضية إلى صفر عند الإنشاء
        ]);
        $this->SendNotification($item,'تم اضافة صوت جديد');

        return $this->handelResponse('', 'The Audio has been added successfully');
    }




    public function storeTag(Request $request)
    {


        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:audios,random_id',
            'tag_name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $ID = $this->getRealID(Audio::class, $request->id)->id;

        // Fetch the existing tag_name value
        $existingAudio = Audio::findOrFail($ID);
        $existingTagName = $existingAudio->tag_name;

        // Merge the new values with the existing array
        $newTagName = $existingTagName . ' ' . $request->tag_name;

        // Update the existing record with the updated 'tag_name'
        $existingAudio->update([
            'tag_name' => $newTagName,
        ]);


        return $this->handelResponse('', 'The tag_name has been added successfully');
    }




    public function store_visit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:audios,random_id'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(Audio::class, $request->id)->id;

        $audio = Audio::findOrFail($ID);

        $audio->increment('visits_count');

        return $this->handelResponse('', 'The Audio has been increment successfully');
    }

    public function Audios_public()
    {
        // Fetch elders with their approved audios
        $data = Elder::with(['audio' => function ($query) {
            $query->where('status', 'Approve');
        }])->where('status', 'Approve')->get();

        // Check if user is authenticated
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;

            // Iterate over the audios to check if each one is in the user's favorites
            $data->each(function ($elder) use ($user_id) {
                $elder->audio->each(function ($audio) use ($user_id) {
                    $audio->is_Favourite = Favirateaudios::where('user_id', $user_id)
                        ->where('audio_id', $audio->id)
                        ->exists();
                });
            });
        }

        // Return the collection of public audios with associated elders
        return AudioPublicResource::collection($data)->resolve();
    }


    // public function Audios_public()
    // {
    //     $data = Elder::with('audio')->Where('status', 'Approve')->get();
    //     return AudioPublicResource::collection($data)->resolve();
    // }



    public function Audios_public_id(Request $request)
{
    $validate = Validator::make($request->all(), [
        'id' => 'required|integer|exists:audios,random_id'
    ]);
    if ($validate->fails()) {
        return response()->json($validate->errors());
    }

    $ID = $this->getRealID(Audio::class, $request->id)->id;

    // Fetch the audio with public status
    $audio = Audio::where('status', 'public')->find($ID);

    // Check if the audio exists and is public
    if (!$audio) {
        return response()->json(['message' => 'Audio not found or not public.'], 404);
    }

    // Check if user is authenticated
    if (auth('sanctum')->check()) {
        $user_id = auth('sanctum')->user()->id;

        // Check if the audio is in the user's favorites
        $isFavorite = Favirateaudios::where('user_id', $user_id)
            ->where('audio_id', $audio->id)
            ->exists();

        // Add 'is_Favourite' key to the response data
        $responseData = AudioResource::make($audio)->resolve();
        $responseData['is_Favourite'] = $isFavorite;

        return response()->json($responseData);
    }

    // If user is not authenticated, return the audio data without favorite status
    return response()->json(AudioResource::make($audio)->resolve());
}

    // public function Audios_public_id(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'id' => 'required|integer|exists:audios,random_id'
    //     ]);
    //     if ($validate->fails()) {
    //         return response()->json($validate->errors());
    //     }
    //     $ID = $this->getRealID(Audio::class, $request->id)->id;

    //     $data = Audio::where('status', 'public')->find($ID);
    //     return AudioResource::make($data)->resolve();
    // }

    // Get audios from db
    public function Get_audio()
    {
        $data = Audio::with('elder')->get();
        return AudioResource::collection($data)->resolve();
        // return $data;
    }


    // get audio Using id jUST // -> details elder

    public function Get_id(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:audios,random_id'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(Audio::class, $request->id)->id;

        $data =  Audio::with('elder')->findOrFail($ID);
        return AudioResource::make($data)->resolve();
    }
    // ,..................



    // public function edit($id){
    //    return Audio::find($id);
    // }

    // update Audio
    public function update_Audio(Request $request)
    {
        // Get Validated Audio Data
        // $Data=$this->validated();
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:audios,random_id',
            'image' => 'image|max:2048',
            'audio' => 'mimes:mp3,wav',
            'status' => 'in:public,private',
            // 'title' => '',
            'tag_name' => 'required|array',
            'Audio_category' => 'required|integer|exists:audios_categories,id'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(Audio::class, $request->id);
        $audio = Audio::find($ID);
        // Step 1: Remove the old file and image
        if ($request->hasFile('image')) {
            if ($audio->image) {
                $this->fileRemove($audio->image);
            }
            //   handle create image
            $image = $this->saveImage($request->file('image'), 'Audio_image');
        } else {
            $image = $audio->image;
        }
        if ($request->hasFile('audio')) {
            if ($audio->audio) {
                $this->fileRemove($audio->audio);
            }

            // handle file create
            $audio_file = $request->file('audio')->store('Audio', 'dr_amr');
        } else {
            $audio_file = $audio->audio;
        }


        $audio->update([
            'image' => $image,
            'audio' => $audio_file,
            'status' => $request->status,
            'title' => $request->title,
            'tag_name' => $request->tag_name, // Wrap the tag in an array and encode to JSON
            'audios_categories' => $request->Audio_category,

        ]);


        return $this->handelResponse('', 'The Audio has been updated successfully');
    }

    public function Delete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:audios,random_id',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(Audio::class, $request->id)->id;
        $audio = Audio::find($ID);
        // Step 1: Remove the old file and image
        if ($audio->image) {
            $this->fileRemove($audio->image);
        }

        if ($audio->audio) {
            $this->fileRemove($audio->audio);
        }

        $audio->Delete();


        return $this->handelResponse('', 'The Audio  has been Deleted successfully');
    }

    public function MostListened()
    {
        $MostListened = Audio::orderBy('visits_count', 'desc')->take(3)->get();

        return MostListenedsource::collection($MostListened)->resolve();
    }
}
