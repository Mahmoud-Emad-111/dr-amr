<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Favirateaudios;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavirateAudioController extends Controller
{
    use RandomIDTrait;
    public  function FavirateAudio(Request $request){
        $validate=Validator::make($request->all(),[
            'audio_id'=> 'required|integer|exists:audios,random_id',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $audio_id=$this->getRealID(Audio::class,$request->audio_id)->id;

        $user_id= auth('sanctum')->user()->id;

        Favirateaudios::create([
            'user_id'=>$user_id,
            'audio_id'=>$audio_id,

        ]);
        return Audio::find($audio_id)->audio;
    }
<<<<<<< HEAD


=======
    
>>>>>>> 4da57474ec17f6fc8bd2a781ec86725735aef274
}