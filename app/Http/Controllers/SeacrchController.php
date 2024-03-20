<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\BookRequests;
use App\Http\Resources\ArticlsResource;
use App\Http\Resources\AudioPublicResource;
use App\Http\Resources\AudioResource;
use App\Http\Resources\ElderResource;
use App\Http\Resources\IdBookResource;
use App\Models\Articles;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Elder;
use App\Models\Faviratebooks;
use App\Models\Image_Categories;
use App\Models\Linktag_audio;
use App\Models\Tag;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsEmpty;

class SeacrchController extends Controller
{
    use RandomIDTrait;
    // Search audio by title
    public function search_audio(Request $request)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $audioByTitle = Audio::where('status', 'Public')
                ->where(function ($query) use ($request) {
                    $query->where("tag_name", 'LIKE', "%" . $request->title . "%")
                        ->orWhere("title", 'LIKE', "%" . $request->title . "%");
                })
                ->select('audios.*', 'favirateaudios.audio_id as isFav')
                ->leftJoin('favirateaudios', function ($join) use ($user_id) {
                    $join->on('audios.id', '=', 'favirateaudios.audio_id')
                        ->where('favirateaudios.user_id', '=', $user_id);
                })
                ->get();
        } else {
            $audioByTitle = Audio::where('status', 'Public')
                ->where(function ($query) use ($request) {
                    $query->where("tag_name", 'LIKE', "%" . $request->title . "%")
                        ->orWhere("title", 'LIKE', "%" . $request->title . "%");
                })
                ->get();
        }

        return AudioResource::collection($audioByTitle)->resolve();
    }

    // Search elder by name favirateelders
    public function search_elder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        // Get user ID if authenticated
        $user_id = auth('sanctum')->check() ? auth('sanctum')->user()->id : null;

        // Query elders based on search criteria and status
        $ElderByname = Elder::where('status', 'Approve')
            ->where(function ($query) use ($request) {
                $query->where("tag", 'LIKE', "%" . $request->name . "%")
                    ->orWhere("name", 'LIKE', "%" . $request->name . "%");
            })
            ->select('elders.*', 'favirateelders.elder_id as isFav')
            ->leftJoin('favirateelders', function ($join) use ($user_id) {
                $join->on('elders.id', '=', 'favirateelders.elder_id')
                    ->where('favirateelders.user_id', '=', $user_id);
            })
            ->get();

        return AudioPublicResource::collection($ElderByname)->resolve();
    }

    // Search articles by title
        public function search_articles(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        // Get user ID if authenticated
        $user_id = auth('sanctum')->check() ? auth('sanctum')->user()->id : null;

        // Query articles based on search criteria and status
        $data = Articles::where('status', 'Public')
        ->where(function ($query) use ($request) {
            $query->where("tag_name", 'LIKE', "%" . $request->title . "%")
                ->orWhere("title", 'LIKE', "%" . $request->title . "%");
        })
        ->select('articles.*', 'faviratearticles.articles_id as isFav') // تحديد اسم العمود الصحيح هنا
        ->leftJoin('faviratearticles', function ($join) use ($user_id) {
            $join->on('articles.id', '=', 'faviratearticles.articles_id')
                ->where('faviratearticles.user_id', '=', $user_id);
        })
        ->get();


        return ArticlsResource::collection($data)->resolve();
    }

    // public function search_articles(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'title' => 'required',
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json($validate->errors());
    //     }
    //     // Check if the title matches the status
    //     $data = Articles::where("tag_name", 'LIKE', "%" . $request->title . "%")->where('status', 'Public')
    //         ->orWhere("title", 'LIKE', "%" . $request->title . "%")
    //         ->get();
    //     return ArticlsResource::collection($data)->resolve();
    // }

    // Search Book by name
    public function search_Book(Request $request)
    {
        // Check if the title matches the status

        $validate = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        // Get user ID if authenticated
        $user_id = auth('sanctum')->check() ? auth('sanctum')->user()->id : null;
        // Query books based on search criteria and status
        $data = Book::where('status', 'Public')
            ->where(function ($query) use ($request) {
                $query->where("tag_name", 'LIKE', "%" . $request->name . "%")
                    ->orWhere("name", 'LIKE', "%" . $request->name . "%");
            })
            ->select('books.*', 'faviratebooks.book_id as isFav')
            ->leftJoin('faviratebooks', function ($join) use ($user_id) {
                $join->on('books.id', '=', 'faviratebooks.book_id')
                    ->where('faviratebooks.user_id', '=', $user_id);
            })
            ->get();

        return IdBookResource::collection($data)->resolve();
    }
}
