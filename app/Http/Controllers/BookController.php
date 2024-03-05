<?php

namespace App\Http\Controllers;
use App\Http\Requests\BookRequests;
use App\Http\Requests\books_Updated_Request;
use App\Http\Resources\BookElderResource;
use App\Http\Resources\Books_and_Elder_resource;
use App\Http\Resources\FileBookResoure;
use App\Http\Resources\IdBookResource;
use App\Models\Book;
use App\Models\Elder;
use App\Traits\SaveImagesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    use SaveImagesTrait;
  // get all data Books
    public function Get(){
        $Get_resource =  Book::all();
        return FileBookResoure::collection($Get_resource);
    }


    public function store(BookRequests $request)
    {


        // handle file create
        $file = $request->file('file')->store('books_images','dr_amr');



        // handle image create
        $image = $this->saveImage($request->file('image'), 'books_images');


        //    create db data -> books
        Book::create([
            'name' => $request->name,
            'file' => $file,
            'image' => $image,
            'status' => $request->status,
            'books_categories_id' => $request->categories_id

        ]);


        return $this->handelResponse('','The book has been added successfully');
    }


    // get elder -> Book
    public function GetDataId(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:books,random_id',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Book::class, $request->id);
        $data_id =  Book::with('elder')->findOrFail($ID);
        return new Books_and_Elder_resource( $data_id);

    }


    //   Edit books and Update

    public function Edit_Book(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:books,id',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Book::class, $request->id);
        $data_id = Book::find($ID);
        return IdBookResource::make($data_id);

    }
   // Update Book
    public function Update_Book(books_Updated_Request $request)
    {
        $ID=$this->getRealID(Book::class, $request->id);
        $data =  Book::find($ID);
       // Step 1: Remove the old file and image
          if ($data->file) {
            $this->fileRemove($data->file);
          }

          if ($data->image) {
            $this->fileRemove($data->image);
          }




        // handle file update
        $file = $request->file('file')->store('books_images','dr_amr');

        // handle image update
        $image = $this->saveImage($request->file('image'), 'books_images');


        $data->update([
            'name' => $request->name,
            'file' => $file,
            'image' => $image,
            'status' => $request->status,
        ]);

       return FileBookResoure::make($data);
    }

    public function Delete(Request $request){
    $validate=Validator::make($request->all(),[
        'Book_id'=> 'required|integer|exists:books,random_id',
    ]);

    if($validate->fails()){
        return response()->json($validate->errors());
    }

    $Book =  Book::find($request->Book_id);
    // Step 1: Remove the old file and image
    if ($Book->file) {
        $this->fileRemove($Book->file);
    }

    if ($Book->image) {
        $this->fileRemove($Book->image);
    }

    $Book->delete();

    return $this->handelResponse('','The book  has been Deleted successfully');

    }


}



