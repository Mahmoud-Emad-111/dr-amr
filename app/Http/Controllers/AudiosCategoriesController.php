<?php

namespace App\Http\Controllers;

use App\Http\Resources\All_Audios_CategoriesResources;
use App\Http\Resources\AudioCategoriesResource;
use App\Http\Resources\AudioResource;
use App\Http\Resources\ELderAllAudioResource;
use App\Models\Audio;
use App\Models\AudiosCategories;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AudiosCategoriesController extends Controller
{

    use SaveImagesTrait,RandomIDTrait;
        //
    ############ Add category title #####################3
    public function Store(Request $request){
        $validate=Validator::make($request->all(),[
            'title'=> 'required'
            ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        AudiosCategories::create([
            'title'=>$request->title,
            'random_id'=>$this->RandomID(),
        ]);
        return $this->handelResponse('','The category has been added successfully');
    }
    ############## Get all Audio categories ##################
    public function Get(){
        $data=AudiosCategories::all();
        return AudioCategoriesResource::collection($data)->resolve();
    }

    ############ Update image category data #################
    public function Update(Request $request){
        $validate=Validator::make($request->all(),[
            'title'=> 'required',
            'id'=>'required|exists:audios_categories,random_id|integer'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(AudiosCategories::class, $request->id);
        AudiosCategories::find($ID->id)->update([

            'title'=>$request->title,
        ]);
        return $this->handelResponse('','The category has been updated successfully');
    }

    ################# Delete image category #################################
    public function Delete(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=>'required|exists:audios_categories,random_id|integer'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(AudiosCategories::class, $request->id);
        AudiosCategories::find($ID->id)->delete();
        return $this->handelResponse('','The category has been successfully deleted');
    }


    public function Get_audios_with_category(Request $request){
        $validate=Validator::make($request->all(),[
            'category_id'=>'required|exists:audios_categories,random_id|integer'
            ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        // $data=AudiosCategories::with('audios')->find($request->category_id);
         $ID=$this->getRealID(AudiosCategories::class, $request->category_id)->id;
        // $data=Audio::with('elder')->where('audios_categories_id',$ID)->get();
         $data=AudiosCategories::with('audios')->find($ID)->audios;
        //  return AudioResource::collection($data->audios)->resolve();
        return  All_Audios_CategoriesResources::collection( $data)->resolve();

        // return $data->audios;
    }

}
