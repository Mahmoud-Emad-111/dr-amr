<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdBookResource;
use App\Models\Book;
use App\Models\Faviratebooks;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavirateBookController extends Controller
{
    use RandomIDTrait;

    public function FavirateBook(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'book_id' => 'required|integer|exists:books,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $user = auth('sanctum')->user();
        $book_id = $this->getRealID(Book::class, $request->book_id)->id;

        $favoriteBook = Faviratebooks::where('user_id', $user->id)
            ->where('book_id', $book_id)
            ->first();

        if ($favoriteBook) {
            $favoriteBook->delete();
            return $this->handelResponse('', 'The Book has been removed from your favorites');
        } else {
            Faviratebooks::create([
                'user_id' => $user->id,
                'book_id' => $book_id,
            ]);
            return $this->handelResponse('', 'The Book has been added to your favorites');
        }
    }

    public function Get_Favirate_Books()
    {
        $user_id = auth('sanctum')->user()->id;
        $data = User::with('Favirate_Books')->find($user_id)->favirate_books;
        return IdBookResource::collection($data)->resolve();
    }
    public function deleteFavoriteBook(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'book_id' => 'required|exists:faviratebooks,book_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        // Retrieve the book ID from the request
        $bookId = $request->book_id;

        // Find and delete the favorite Book record
        $favoriteBook = Faviratebooks::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->delete();

        // Check if the Book was found and deleted
        if ($favoriteBook) {
            return response()->json(['message' => 'Favorite Books deleted successfully']);
        } else {
            return response()->json(['error' => 'Favorite Books not found'], 404);
        }
    }
}
