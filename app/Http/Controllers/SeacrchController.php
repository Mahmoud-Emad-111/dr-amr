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
            // Check if the title matches the status
            $audioByTitle = Audio::where("tag_name", 'LIKE', "%" . $request->title . "%")
            ->orWhere("title", 'LIKE', "%" . $request->title . "%")
            ->get();
            return AudioResource::collection($audioByTitle)->resolve();
        }
         // Search elder by name
        public function search_elder(Request $request)
        {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors());
            }

            // Check if the title matches the status
            $ElderByname = Elder::where("tag", 'LIKE', "%" . $request->name . "%")->where('status','Approve')
            ->orWhere("name", 'LIKE', "%" . $request->name . "%")
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
            // Check if the title matches the status
            $data = Articles::where("tag_name", 'LIKE', "%" . $request->title . "%")->where('status','Public')
            ->orWhere("title", 'LIKE', "%" . $request->title . "%")
            ->get();
           return ArticlsResource::collection($data)->resolve();

        }

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

            $data = Book::where("tag_name", 'LIKE', "%" . $request->name . "%")->where('status','Public')
            ->orWhere("name", 'LIKE', "%" . $request->name . "%")
            ->get();

            return IdBookResource::collection($data)->resolve();

        }

    }


