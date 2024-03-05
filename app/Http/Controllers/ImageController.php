<?php

namespace App\Http\Controllers;

use App\Http\Resources\imageResource;
use App\Models\Image;
use App\Models\Image_Categories;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use App\Traits\StorageFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    use SaveImagesTrait, RandomIDTrait,StorageFileTrait;
    #############Store Image ########
    public function Store(Request $request){
        $validate=Validator::make($request->all(),[
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_categories_id'=>'required|integer|exists:image_categories,random_id',
            'status'=>'required|in:Public,Private',

        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
       $ID=$this->getRealID(Image_Categories::class, $request->image_categories_id)->id;

        $image = $this->saveImage($request->file('image'), 'images');


        Image::create([
            'image'=> $image,
            'image_categories_id'=> $ID,
            'status'=>$request->status,
            'random_id'=>$this->RandomID(),
        ]);
        return $this->handelResponse('','The image has been added successfully');
        // return imageResource::make($data);

        }
    ############ update data image##############33
    public function Update(Request $request){
        $validate=Validator::make($request->all(),[
            'image'=> 'image',
            'status'=>'required|in:Public,Private',
            'id'=>'required|integer|exists:images,random_id',
            'category_id'=>'required|exists:image_categories,random_id|integer'
            ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }

        $ID=$this->getRealID(Image::class, $request->id);
        $image=Image::findOrFail($ID)->image;
        if ($request->hasFile('image')) {
            $this->fileRemove($image);
            $image=$this->saveImage($request->file('image'), 'image');
        }
        Image::find($ID)->Update([
            'status'=>$request->status,
            'image'=> $image,
            'image_categories_id'=>$request->category_id,
        ]);

        return $this->handelResponse('','The Image has been updated successfully');
    }
    #####Get all data Image ################
    public function Get(){
        $data=Image::with('image_category')->get();
        return imageResource::collection($data)->resolve();
    }
    public function Get_public(){
        $data=Image::Where('status','Public');
        return imageResource::collection($data)->resolve();
    }

    ################Delete Image with id image ####################
    public function Delete(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=>'required|exists:Image,random_id|integer'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Image::class, $request->id)->id;
        $image=Image::find($ID);
        $this->fileRemove($image->image);
        $image->delete();
        return $this->handelResponse('','The Image has been delate successfully');

    }

}
