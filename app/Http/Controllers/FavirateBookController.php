<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdBookResource;
use App\Models\Book;
use App\Models\Faviratebooks;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavirateBookController extends Controller
{
    use RandomIDTrait;
    
    public  function FavirateBook(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $book_id = $this->getRealID(Book::class, $request->book_id)->id;

        $user_id = auth('sanctum')->user()->id;

        Faviratebooks::create([
            'user_id' => $user_id,
            'book_id' => $book_id,

        ]);
        return $this->handelResponse('','The Book has been added to your favorites');
    }
    public function Get_Favirate_Books(){
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('Favirate_Books')->find($user_id)->favirate_books;
       return IdBookResource::collection($data)->resolve();

   }
}
