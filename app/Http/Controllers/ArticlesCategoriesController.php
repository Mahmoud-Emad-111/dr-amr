<?php

// done
namespace App\Http\Controllers;

use App\Http\Resources\ArticlesCategoriesResources;
use App\Http\Resources\ArticlesFromCategoriesResources;
use App\Http\Resources\ArticlsResource;
use App\Models\Articles;
use App\Models\Articles_Categories;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticlesCategoriesController extends Controller
{
    //
    use RandomIDTrait;
    ################## create Articles Categories ###############
    public function Store(Request $request){
        $Data=Validator::make($request->all(),[
            'title' => 'required|string',
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        Articles_Categories::create([
            'title'=>$request->title,
            'random_id'=>$this->RandomID(),
        ]);
      return  $this->handelResponse('','The category has been added successfully');

    }
    ################ Get All Articles Categories ######################
    public function Get(){
        $date=Articles_Categories::all();
        return ArticlesCategoriesResources::collection($date)->resolve();
    }
    ############ Get Articles public used id#################33333

    public function Get_Articles_public_From_Category(Request $request){
        $Data=Validator::make($request->all(),[
            'id' => 'required|exists:articles_categories,random_id',
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $id=$this->getRealID(Articles_Categories::class,$request->id)->id;
         $data=Articles::with('elder')->Where('status','public')->Where('articles_categories_id',$id)->Get();
        return ArticlesFromCategoriesResources::collection($data)->resolve();

    }
    ############ Get Articles public or private used id#################33333
    public function Get_Articles_From_Category(Request $request){
        $Data=Validator::make($request->all(),[
            'id' => 'required|exists:articles_categories,random_id',
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $id=$this->getRealID(Articles_Categories::class,$request->id)->id;
         $data=Articles::with('elder')->Where('articles_categories_id',$id)->Get();
        return ArticlesFromCategoriesResources::collection($data)->resolve();

    }

    public function Update(Request $request){
        $Data=Validator::make($request->all(),[
            'id' => 'required|exists:articles_categories,random_id',
            'title' => 'required|string',

        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $id=$this->getRealID(Articles_Categories::class,$request->id)->id;
        Articles_Categories::find($id)->Update([
            'title'=>$request->title,
        ]);
        return  $this->handelResponse('','The category has been Updated successfully');
    }
    public function Delete(Request $request){
        $Data=Validator::make($request->all(),[
            'id' => 'required|exists:articles_categories,random_id',

        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $id=$this->getRealID(Articles_Categories::class,$request->id)->id;
        Articles_Categories::find($id)->Delete();
        return  $this->handelResponse('','The category has been Deleted successfully');
    }
}
