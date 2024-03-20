<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Http\Resources\SettingsResource;
use App\Models\Settings;
use App\Models\User;
use App\Notifications\SendPrivateCode;
use App\Traits\SaveImagesTrait;
use App\Traits\StorageFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    //
    use SaveImagesTrait,StorageFileTrait;
    public function Store(SettingsRequest $request){
        $logo=null;
        $image=null;
        if ($request->hasFile('image')) {
            # code...
            $image=$this->saveImage($request->file('image'),'main_image');
        }
        if ($request->hasFile('logo')) {
            # code...
            $logo=$this->saveImage($request->file('logo'),'main_image');
        }
        Settings::create([
            'facebook'=>$request->facebook,
            'whatsapp'=>$request->whatsapp,
            'messenger'=>$request->messenger,
            'instagram'=>$request->instagram,
            'image'=>$image,
            'logo'=>$logo,
            'code_private'=>$request->code_private,

            'prayer_timings'=>$request->prayer_timings,
        ]);
        return $this->handelResponse('','Settings have been added successfully');
    }

    public function Update(Request $request){
        $validate=Validator::make($request->all(),[
            //
            'facebook'=>'url',
            'whatsapp'=>'url',
            'messenger'=>'url',
            'instagram'=>'url',
            'image'=>'image',
            'logo'=>'image',
            'prayer_timings'=>'in:0,1',
            'code_private'=>'numeric',

            ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $image=Settings::orderBy('id', 'desc')->first()->image;
        $logo=Settings::orderBy('id', 'desc')->first()->logo;
        if ($request->hasFile('image')) {
            $this->fileRemove($image);
            $image=$this->saveImage($request->file('image'), 'main_image');
        }
        if ($request->hasFile('logo')) {
            $this->fileRemove($logo);
            $image=$this->saveImage($request->file('logo'), 'main_image');
        }
        Settings::find(1)->Update([
            'facebook'=>$request->facebook,
            'whatsapp'=>$request->whatsapp,
            'messenger'=>$request->messenger,
            'instagram'=>$request->instagram,
            'image'=>$image,
            'logo'=>$logo,
            'code_private'=>$request->code_private,

            'prayer_timings'=>$request->prayer_timings,
        ]);
        return $this->handelResponse('','The Settings has been updated successfully');

    }
    Public function Get(){
        $settins=Settings::orderBy('id', 'desc')->first();
        return SettingsResource::make($settins)->resolve();
    }

    public function SendCode(Request $request){
        $validate=Validator::make($request->all(),[
            'code'=>'required',
            'User_id'=>'required|exists:users,id'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }

        $user=User::find($request->User_id);
        $user->notify(new SendPrivateCode($request->code));
        return $this->handelResponse('','The code has been sent successfully');
    }
}
