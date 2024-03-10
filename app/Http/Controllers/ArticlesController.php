<?php

namespace App\Http\Controllers;

use App\Http\Requests\Articles\ArticleUpdateRequest;
use App\Http\Requests\ArticlesRequest;
use App\Http\Resources\ArticlsResource;
use App\Http\Resources\RelationArticlsElderResource;
use App\Http\Resources\RelationArticlsResource;
use App\Models\Articles;
use App\Models\Articles_Categories;
use App\Models\Elder;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use App\Traits\StorageFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    use SaveImagesTrait, RandomIDTrait, StorageFileTrait;
    public function Store(Request $request){
        //   handle create image
        // Get Validated Articles
        $Data=Validator::make($request->all(),[
            'title' => 'required',
            'image' =>  'required|image',
            'content'=>'required',
            'elder_id' => 'required|exists:elders,random_id',
            'articles_categories_id'=>'required|exists:articles_categories,random_id',
            'status'=>'required|in:Public,Private',
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $category=$this->getRealID(Articles_Categories::class,$request->articles_categories_id)->id;
        $elder=$this->getRealID(Elder::class,$request->elder_id)->id;
        $image=$this->saveImage($request->file('image'), 'Articles_image');

        Articles::create([
        'title' => $request->title,
        'image' => $image,
        'content'=> $request->content,
        'elder_id'=>$elder,
        'status'=>$request->status,
        'tag_name' => $request->tag_name,
        'articles_categories_id'=>$category,
        'random_id'=>$this->RandomID(),

    ]);
    return  $this->handelResponse('','The Articles has been added successfully');

    }

    public function storeTag(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:articles,random_id',
            'tag_name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $ID = $this->getRealID(Articles::class, $request->id)->id;

        // Fetch the existing tag_name value
        $existingAudio = Articles::findOrFail($ID);
        $existingTagName = $existingAudio->tag_name;

        // Merge the new values with the existing array
        $newTagName = $existingTagName.' '. $request->tag_name;

        // Update the existing record with the updated 'tag_name'
        $existingAudio->update([
            'tag_name' => $newTagName,
        ]);


        return $this->handelResponse('', 'The tag_name has been added successfully');
    }


     // get all data Books
    public function Get(){
        $data = Articles::get();
        return ArticlsResource::collection($data)->resolve();
    }
         // get all Articles public
    public function Get_public(){
        $data = Articles::where('status','Public')->get();
        return ArticlsResource::collection($data)->resolve();
    }




    ########### Get Public Articles using id ##########################3
    public function Get_public_Id(Request $request){
        $Data=Validator::make($request->all(),[
            'id' => 'required|exists:articles,random_id',
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }

        $id=$this->getRealID(Articles::class,$request->id)->id;
         $data=Articles::with('elder')->Where('status','public')->find($id);
        if($data==''){
            return $this->handelError('You do not have permission to access this content');
        }else{
            $articles = Articles::findOrFail($id);

            $articles->increment('visit_count');

            return RelationArticlsElderResource::make($data)->resolve();
        }

    }



    ########### Get Public OR Private Articles using id ##########################3
    public function Get_Id(Request $request){
        $Data=Validator::make($request->all(),[
            'id' => 'required|exists:articles,random_id',
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
         $id=$this->getRealID(Articles::class,$request->id)->id;
         $data=Articles::with('elder')->find($id);
        return RelationArticlsElderResource::make($data)->resolve();

    }
    ############## update Article#################

    public function Update(Request $request){
        $Data=Validator::make($request->all(),[
            'title' => 'required',
            'image' =>  'image',
            'content'=>'required',
            'tag_name'=>'array',
            'elder_id' => 'required|exists:elders,random_id',
            'articles_categories_id'=>'required|exists:articles_categories,random_id',
            'status'=>'required|in:Public,Private',
            'id'=>'required|exists:articles,random_id'
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $category=$this->getRealID(Articles_Categories::class,$request->articles_categories_id)->id;
        $elder=$this->getRealID(Elder::class,$request->elder_id)->id;
        $articles_id=$this->getRealID(Articles::class,$request->id)->id;
        $articles =  Articles::find($articles_id);

        if ($request->hasFile('image')) {

            $this->fileRemove($articles->image);

            $image=$this->saveImage($request->file('image'), 'Articles_image');
        }else{
        $image=$articles->image;
        }

        $articles->Update([
        'title' => $request->title,
        'image' => $image,
        'content'=> $request->content,
        'elder_id'=>$elder,
        'tag_name' => $request->tag_name,
        'status'=>$request->status,
        'articles_categories_id'=>$category,

    ]);
    return  $this->handelResponse('','The Articles has been update successfully');

    }

    ############## Delete Article#################
    public function Delete(Request $request){
        $Data=Validator::make($request->all(),[
            'id'=>'required|exists:articles,random_id'
        ]);
        if($Data->fails()){
            return response()->json($Data->errors());
        }
        $articles_id=$this->getRealID(Articles::class,$request->id)->id;
        $articles =  Articles::find($articles_id);
        $this->fileRemove($articles->image);
        $articles->delete();
        return  $this->handelResponse('','The Articles has been delete successfully');

    }

}
