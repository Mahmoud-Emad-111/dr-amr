<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCategoriesAndBooksResource;
use App\Http\Resources\BookCategoriesResource;
use App\Models\BooksCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\FuncCall;

class BooksCategoriesController extends Controller
{
    //
    public function insert(Request $request ){
        $validate=Validator::make($request->all(),[
            'title'=> 'required|max:255',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        BooksCategories::create([
            'title'=>$request->title,
        ]);
        return $this->handelResponse('','The book Category has been added successfully');
    }
    public function get(){
        $data=BooksCategories::all();
        return BookCategoriesResource::collection($data)->resolve();
    // return $data;
    }
    public function Get_Books(Request $request){
        $validate=Validator::make($request->all(),[
            'category_id'=> 'required|integer|exists:books_categories,random_id',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(BooksCategories::class, $request->category_id);
        $data=  BooksCategories::with('Books')->findOrFail($ID);
        return new BookCategoriesAndBooksResource($data);

    }
    public function Update(Request $request){
        $validate=Validator::make($request->all(),[
            'title'=> 'required|max:255',
            'category_id'=> 'required|integer|exists:books_categories,random_id',

        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $category=BooksCategories::find($request->id);
        $category->update([
            'title'=>$request->title,
        ]);

        return $this->handelResponse('','The book Category has been Updated successfully');

    }
    public function Delete(Request $request){
        $validate=Validator::make($request->all(),[
            'category_id'=> 'required|integer|exists:books_categories,random_id',

        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(BooksCategories::class, $request->category_id);
        BooksCategories::find($ID)->delete();
        return $this->handelResponse('','The book Category has been Deleted successfully');

    }


}
