<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdBookResource;
use App\Models\Book;
use App\Models\DownloadBook;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\RandomIDTrait;
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
        return asset(Storage::url(Book::find($book_id)->name));
    }
    public function getBook()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('book')->find($user_id);
        return IdBookResource::collection($data->image)->resolve();

    }
}
