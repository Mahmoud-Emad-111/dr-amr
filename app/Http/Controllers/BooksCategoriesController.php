<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCategoriesAndBooksResource;
use App\Http\Resources\BookCategoriesResource;
use App\Http\Resources\FileBookResoure;
use App\Models\Book;
use App\Models\BooksCategories;
use App\Models\Main_Categories_Book;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\FuncCall;

class BooksCategoriesController extends Controller
{
    //
    //
    use RandomIDTrait;

    public function Store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'ID_Main_Category' => 'required|exists:main__categories__books,random_id'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $Main_id = $this->getRealID(Main_Categories_Book::class, $request->ID_Main_Category)->id;

        BooksCategories::create([
            'title' => $request->title,
            'main__categories__books_id' => $Main_id,
            'random_id' => $this->RandomID()
        ]);
        return $this->handelResponse('', 'The book Category has been added successfully');
    }
    ########### Get Book Category ###########
    public function Get()
    {


        $data = BooksCategories::all();
        return BookCategoriesResource::collection($data)->resolve();
        // return $data;
    }
    ######### Get all  books private or public  From category #########
    public function Get_Books(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:books_categories,random_id',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(BooksCategories::class, $request->category_id)->id;
        $data =  BooksCategories::with('Books')->findOrFail($ID);
        return  BookCategoriesAndBooksResource::make($data)->resolve();
    }
    ######### Get all  books  public  From category #########



    public function Get_Books_public(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:books_categories,random_id',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(BooksCategories::class, $request->category_id)->id;
        $data = Book::Where('books_categories_id', $ID)->Where('status', 'public')->Get();
        return FileBookResoure::collection($data)->resolve();
        // $data=  BooksCategories::with('Books')->where('status','Public')->findOrFail($ID);
        // return  BookCategoriesAndBooksResource::make($data)->resolve();

    }



    public function Update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'category_id' => 'required|integer|exists:books_categories,random_id',

        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(BooksCategories::class, $request->category_id)->id;

        BooksCategories::find($ID)->Update([
            'title' => $request->title,

        ]);

        return $this->handelResponse('', 'The book Category has been Updated successfully');
    }
    public function Delete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:books_categories,random_id',

        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $ID = $this->getRealID(BooksCategories::class, $request->category_id)->id;
        BooksCategories::find($ID)->delete();
        return $this->handelResponse('', 'The book Category has been Deleted successfully');
    }
}
