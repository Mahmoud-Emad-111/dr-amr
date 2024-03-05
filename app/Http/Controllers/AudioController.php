<?php

namespace App\Http\Controllers;

use App\Http\Requests\AudioRequest;
use App\Http\Resources\AudioAllElderResource;
use App\Http\Resources\AudioResource;
use App\Models\Audio;
use App\Models\AudiosCategories;
use App\Models\Elder;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// use App\Models\Elder;

class AudioController extends Controller
{

    use SaveImagesTrait, RandomIDTrait;
    public function store(AudioRequest $request)
    {

        $elder_id = $this->getRealID(Elder::class, $request->elder_id)->id;
        $category_id = $this->getRealID(AudiosCategories::class, $request->Audio_category)->id;
        //   handle create image
        $image = $this->saveImage($request->file('image'), 'Audio_image');
        // handle file create
        $audio = $request->file('audio')->store('Audio', 'dr_amr');
        // Get Validated Audio Data

        Audio::create([
            'title' => $request->title,
            'image' => $image,
            'audio' => $audio,
            'status' => $request->status,
            'elder_id' => $elder_id,
            'audios_categories_id' => $category_id,
            'random_id' => $this->RandomID(),
            'visits_count' => 0, // تعيين قيمة الزيارات الافتراضية إلى صفر عند الإنشاء
        ]);
        return $this->handelResponse('', 'The Audio has been added successfully');
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
         $data = Elder::Where('status','Approve')->get();
        return AudioResource::collection($data)->resolve();
    }

    public function Audios_public_id(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:audios,random_id'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(Audio::class, $request->id)->id;

        $data = Audio::where('status', 'public')->find($ID);
        return AudioResource::collection($data)->resolve();
    }

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
            'status' => 'required|in:public,private',
            'title' => 'required',
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
        $ID = $this->getRealID(Audio::class, $request->id);
        $audio = Audio::find($ID);
        // Step 1: Remove the old file and image
        if ($audio->image) {
            // $oldFilePath = unlink(public_path('uploads/'. $audio->image));
            $this->fileRemove($audio->image);
        }

        if ($audio->audio) {
            $this->fileRemove($audio->audio);
        }

        $audio->Delete();


        return $this->handelResponse('', 'The Audio  has been Deleted successfully');
    }
}
