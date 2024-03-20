<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlesfavirateUserResource;
use App\Models\Articles;
use App\Models\FavirateArticles;
use App\Models\User;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavirateArticlesController extends Controller
{
    use RandomIDTrait;

    public function FavirateArticles(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'articles_id' => 'required|integer|exists:articles,random_id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $user = auth('sanctum')->user();
        $articles_id = $this->getRealID(Articles::class, $request->articles_id)->id;

        // $favoriteArticles = FavirateArticles::where('user_id', $user->id)
        //     ->where('articles_id', $articles_id)
        //     ->first();
        $favoriteArticles = Faviratearticles::where('user_id', $user->id)
            ->where('articles_id', $articles_id)
            ->first();
        if ($favoriteArticles) {
            $favoriteArticles->delete();
            return $this->handelResponse('', 'The Articles has been removed from your favorites');
        } else {
            Faviratearticles::create([
                'user_id' => $user->id,
                'articles_id' => $articles_id,
            ]);
            return $this->handelResponse('', 'The Articles has been added to your favorites');
        }
    }

    // public function Get_Favirate_Articles()
    // {
    //     $user_id = auth('sanctum')->user()->id;

    //     $user = User::find($user_id);
    //     $favirateArticles = $user->favirateArticles;

    //     return ArticlesfavirateUserResource::collection($favirateArticles)->resolve();
    // }


    public function Get_Favirate_Articles()
    {
        $user_id = auth('sanctum')->user()->id;

        $data = User::with('Favirate_Articles')->find($user_id)->Favirate_Articles;

        return ArticlesfavirateUserResource::collection($data)->resolve();
    }

    public function deleteFavoriteArticles(Request $request)
    {
        $user = Auth::user();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'articles_id' => 'required|exists:FavirateArticles,articles_id,user_id,' . $user->id,
        ]);

        if ($data->fails()) {
            return response()->json($data->errors());
        }

        $ArticlesId = $request->articles_id;
        $favoriteSong = FavirateArticles::where('user_id', $user->id)
            ->where('articles_id', $ArticlesId)
            ->delete();

        // Check if the song was found and deleted
        if ($favoriteSong) {
            return response()->json(['message' => 'Favorite song deleted successfully']);
        } else {
            return response()->json(['error' => 'Favorite song not found'], 404);
        }
    }
}
