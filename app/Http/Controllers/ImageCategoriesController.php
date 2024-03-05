<?php

namespace App\Http\Controllers;

use App\Http\Resources\imagecategoriesResource;
use App\Http\Resources\ImageCategoryResource;
use App\Models\Image;
use App\Models\Image_Categories;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCategoriesController extends Controller
{
    use RandomIDTrait;
    //
    ############ Add category title #####################3
    public function Store(Request $request){
        $validate=Validator::make($request->all(),[
            'title'=> 'required'
            ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        Image_Categories::create([
            'title'=>$request->title,
            'random_id'=>$this->RandomID(),
        ]);
        return $this->handelResponse('','The category has been added successfully');
    }
    ############## Get all image categories ##################
    public function Get(){
        $data = Image_Categories::all();
        return ImageCategoryResource::collection( $data )->resolve();

    }

    // this Get iamges from table images Model Image
    public function Get_data_from_Images(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:image_categories,random_id',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
         $ID=$this->getRealID(Image_Categories::class, $request->id);
        $data =  Image_Categories::with('Image')->find($ID);

        return  imagecategoriesResource::collection($data)->resolve();
    }


    ############ Update image category data #################
    public function Update(Request $request){
        $validate=Validator::make($request->all(),[
            'title'=> 'required',
            'id'=>'required|exists:image_categories,random_id|integer'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Image_Categories::class, $request->id)->id;
        $category=Image_Categories::find($ID);
        $category->update([

            'title'=>$request->title,
        ]);
        return $this->handelResponse('','The category has been updated successfully');
    }
    ################# Delete image category #################################
    public function Delete(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=>'required|exists:image_categories,random_id|integer'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Image_Categories::class, $request->id);
        Image_Categories::find($ID)->delete();
        return $this->handelResponse('','The category has been successfully deleted');
    }



}
