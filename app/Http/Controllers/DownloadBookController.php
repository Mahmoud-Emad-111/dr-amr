<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdBookResource;
use App\Models\Book;
use App\Models\DownloadBook;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\RandomIDTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DownloadBookController extends Controller
{
    //    ############ Download audio from user ##################
    use RandomIDTrait;
    public  function DownloadBook(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $book_id = $this->getRealID(Book::class, $request->book_id)->id;

        $user_id = auth('sanctum')->user()->id;

        DownloadBook::create([
            'user_id' => $user_id,
            'book_id' => $book_id,

        ]);
        // return asset(Storage::url(Book::find($book_id)->name));
        return response()->json([
            'Book_url' =>asset(Storage::url(Book::find($book_id)->name)),

            'Massage' =>'The Book has been Downloaded successfully',
        ]);
    }
    public function getBook()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('book')->find($user_id);
        return IdBookResource::collection($data->book)->resolve();
    }
    public function deleteDownloadBook(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'book_id' => 'required|exists:download_books,book_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the book ID from the request
        $bookId = $request->book_id;

        // Find and delete the download Book record
        $downloadBook = DownloadBook::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->delete();

        // Check if the Book was found and deleted
        if ($downloadBook) {
            return response()->json(['message' => 'download Books deleted successfully']);
        } else {
            return response()->json(['error' => 'download Books not found'], 404);
        }
    }
}
