<?php

namespace App\Http\Controllers;

use App\Http\Resources\MainCategoriesResources;
use App\Models\Main_Categories_Book;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MainCategoriesBookController extends Controller
{
    //
    use RandomIDTrait;
 ############### Store Main Category Books #########################
    public function Store(Request $request){
        $validate=Validator::make($request->all(),[
            'title'=>'required|string'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        Main_Categories_Book::create([
            'title'=>$request->title,
            'random_id'=>$this->RandomID(),
        ]);
        return $this->handelResponse('','The Category  has added  successfully');

    }
    ########### Get all Category ##################
    public function Get(){
         $data=Main_Categories_Book::with('SubCategories')->get();
        return MainCategoriesResources::collection($data)->resolve();
    }


    public function Update(Request $request){

        $validate=Validator::make($request->all(),[
            'id'=>'required|exists:main__categories__books,random_id',
            'title'=>'required|string'

        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $id=$this->getRealID(Main_Categories_Book::class,$request->id)->id;
        Main_Categories_Book::find($id)->update([
            'title'=>$request->title,
        ]);
        return $this->handelResponse('','The Category  has updated  successfully');

    }
    
    public function Delete(Request $request){

        $validate=Validator::make($request->all(),[
            'id'=>'required|exists:main__categories__books,random_id',


        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $id=$this->getRealID(Main_Categories_Book::class,$request->id)->id;
        Main_Categories_Book::find($id)->delete();
        return $this->handelResponse('','The Category  has deleted  successfully');

    }

}
