<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequests;
use App\Http\Requests\books_Updated_Request;
use App\Http\Resources\BookElderResource;
use App\Http\Resources\Books_and_Elder_resource;
use App\Http\Resources\FileBookResoure;
use App\Http\Resources\IdBookResource;
use App\Models\Book;
use App\Models\BooksCategories;
use App\Models\Elder;
use App\Models\Faviratebooks;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use App\Traits\SendNotification;
use App\Traits\StorageFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    use SaveImagesTrait, RandomIDTrait, StorageFileTrait, SendNotification;
    // get all data Books


    public function store(BookRequests $request)
    {
        $books_categories_id = $this->getRealID(BooksCategories::class, $request->categories_id)->id;
        // handle file create
        $file = $request->file('file')->store('books_images', 'dr_amr');



        // handle image create
        $image = $this->saveImage($request->file('image'), 'books_images');


        //    create db data -> books
        $item = Book::create([
            'name' => $request->name,
            'file' => $file,
            'image' => $image,
            'status' => $request->status,
            'tag_name' => $request->tag_name,
            'books_categories_id' => $books_categories_id,
            'random_id' => $this->RandomID(),

        ]);

        $this->SendNotification($item, 'تم اضافة كتاب جديد');

        return $this->handelResponse('', 'The book has been added successfully');
    }
    ############## store tag Books ########################
    public function storeTag(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:books,random_id',
            'tag_name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $ID = $this->getRealID(Book::class, $request->id)->id;

        // Fetch the existing tag_name value
        $existingAudio = Book::findOrFail($ID);
        $existingTagName = $existingAudio->tag_name;

        // Merge the new values with the existing array
        $newTagName = $existingTagName . ' ' . $request->tag_name;

        // Update the existing record with the updated 'tag_name'
        $existingAudio->update([
            'tag_name' => $newTagName,
        ]);


        return $this->handelResponse('', 'The tag_name has been added successfully');
    }
    ############## Get public Books ########################33
    public function Get_Public()
    {
        // التحقق من المصادقة

        if (auth('sanctum')->check()) {

            $user_id = auth('sanctum')->user()->id;

            $publicBooks = Book::where('status', 'Public')
                ->select('books.*', 'faviratebooks.book_id as isFav')
                ->leftJoin('faviratebooks', function ($join) use ($user_id) {
                    $join->on('books.id', '=', 'faviratebooks.book_id')
                        ->where('faviratebooks.user_id', '=', $user_id);
                })->get();
        } else {
            // إذا لم يكن المستخدم مصادقًا، يمكنك استرداد الكتب العامة دون بيانات المفضلة
            $publicBooks = Book::where('status', 'Public')->get();
        }

        return FileBookResoure::collection($publicBooks)->resolve();
    }
    //   old
    //   public function Get_Public(){
    //       $Get_resource =  Book::where('status','Public')->Get();
    //       return FileBookResoure::collection($Get_resource)->resolve();
    //   }
    // get  -> Book public
    public function GetDataId(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:books,random_id',
        ]);

        $randomId = $request->id;

        $book = Book::where('random_id', $randomId)->first();

        if (!$book) {
            return response()->json(['message' => 'Book not found.'], 404);
        }

        $responseData = Books_and_Elder_resource::make($book)->resolve();

        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $isFavorite = Faviratebooks::where('user_id', $user_id)->where('book_id', $book->id)->exists();
            $responseData['is_Favourte'] = $isFavorite;
        }
        return response()->json($responseData);
    }
    // public function GetDataId(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'id' => 'required|integer|exists:books,random_id',
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json($validate->errors());
    //     }
    //     $ID = $this->getRealID(Book::class, $request->id);
    //     $data =  Book::findOrFail($ID);
    //     return  Books_and_Elder_resource::make($data[0])->resolve();
    // }

    public function Update(books_Updated_Request $request)
    {
        $ID = $this->getRealID(Book::class, $request->id);
        $Book =  Book::find($ID)[0];
        // Step 1: Remove the old file and image
        if ($request->hasFile('image')) {
            if ($Book->image) {
                $this->fileRemove($Book->image);
            }
            //   handle create image
            $image = $this->saveImage($request->file('image'), 'books_images');
        } else {
            $image = $Book->image;
        }


        if ($request->hasFile('file')) {
            if ($Book->file) {
                $this->fileRemove($Book->file);
            }

            // handle file create
            $file = $this->saveImage($request->file('file'), 'books_images');
        } else {
            $file = $Book->file;
        }

        $Book->update([
            'name' => $request->name,
            'file' => $file,
            'image' => $image,
            'status' => $request->status,
            'tag_name' => $request->tag_name,

        ]);

        return $this->handelResponse('', 'The book has been update successfully');
    }


    public function Delete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Book_id' => 'required|integer|exists:books,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $id = $this->getRealID(Book::class, $request->Book_id);
        $Book =  Book::find($id)[0];
        // Step 1: Remove the old file and image
        if ($Book->file) {
            $this->fileRemove($Book->file);
        }

        if ($Book->image) {
            $this->fileRemove($Book->image);
        }

        $Book->delete();

        return $this->handelResponse('', 'The book  has been Deleted successfully');
    }
    public function LatestVersionBooks()
    {
        $latestBooks = Book::orderBy('created_at', 'desc')->take(5)->get();

        return FileBookResoure::collection($latestBooks)->resolve();
    }

    public function Get_Books_Private()
    {
        $data =  Book::where('status', 'Private')->Get();
        return FileBookResoure::collection($data)->resolve();
    }
}
