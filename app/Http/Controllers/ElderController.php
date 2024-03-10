<?php

namespace App\Http\Controllers;
use App\Http\Requests\ElderRequests;
use App\Http\Resources\AudioAllElderResource;
use App\Http\Resources\ELderAllAudioResource;
use App\Http\Resources\ElderAllBooksCollection;
use App\Http\Resources\ELderAllBooksResource;
use App\Http\Resources\ElderResource;
use App\Http\Resources\EldersApproveResource;
use App\Http\Resources\RelationArticlsElderResource;
use App\Models\Audio;
use App\Models\Elder;
use App\Traits\RandomIDTrait;
use App\Traits\SaveImagesTrait;
use App\Traits\StorageFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ElderController extends Controller{

    use SaveImagesTrait,RandomIDTrait,StorageFileTrait;
    public function store(ElderRequests $request){
        // handle image create
        $image = $this->saveImage($request->file('image'), 'elders_images');

 //    create db data -> books
        Elder::create([
            'name' => $request->name,
            'image' => $image,
            'email' =>$request->email,
            'phone_number'=>$request->phone,
            'tag' =>$request->tag,
            'random_id'=>$this->RandomID(),
            'status'=>$request->status,
        ]);
        return $this->handelResponse('','The Elder  has added  successfully');
    }


    public function storeTag(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:elders,random_id',
            'tag_name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $ID = $this->getRealID(Elder::class, $request->id)->id;

        // Fetch the existing tag_name value
        $existingAudio = Elder::findOrFail($ID);
        $existingTagName = $existingAudio->tag;
         $tags=$request->tag_name.' '.$existingTagName;

        // Update the existing record with the updated 'tag_name'
        $existingAudio->update([
            'tag' => $tags,
        ]);


        return $this->handelResponse('', 'The tag_name has been added successfully');
    }
    //  all Elders data from database
    public function Get(){

        $data_elder = Elder::all();
        return ElderResource::collection($data_elder)->resolve();
    }

    // get elder just data id .
    public function Id_Data_elder(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:elders,random_id',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Elder::class, $request->id);
        $data_elder_id =  Elder::findOrFail($ID);

      return ElderResource::make($data_elder_id[0])->resolve();
    }

    // this create data with database


     // this get id Book ->  Elder
    public function Get_books_elder_id(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:elders,random_id',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }

        $ID=$this->getRealID(Elder::class, $request->id);
        $get_id =  Elder::with('books')->findOrFail($ID);
        return new ELderAllBooksResource( $get_id );

    }
    // this get Audio -> elder -> ID
    public function Get_Audio_Id_Elder(Request $request){
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:elders,random_id',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }

        $ID=$this->getRealID(Elder::class, $request->id)->id;

        $data_Audio = Elder::with('Audio')->findOrFail($ID);

        return new ELderAllAudioResource( $data_Audio);

    }




    //  Edit Elder and Update
    public function Edit_Elder($id){
        $ID=$this->getRealID(Elder::class, $id);
        $data_id_just =  Elder::find($ID);
        return ElderResource::make($data_id_just);
    }

    public function Update_Elder(Request $request){


        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:elders,random_id',
            'name' => 'required|string|max:255',
            'image' => 'image',
            'email' => 'required|email',
            'tag_name' =>'array',
            'phone' => 'required|max:12',
            'status'=>'required|in:Pending,Approve',

        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Elder::class, $request->id)->id;
        $elder =  Elder::find($ID);
        $image=$elder->image;
        // return $elder->image;
        if ($request->hasFile('image')) {
            # code...
                    // Step 1: Remove the old file and image

            if ($elder->image) {

                $this->fileRemove($elder->image);
            }

            // handle update IMAGE elder
            $image = $this->saveImage($request->file('image'), 'elders_images');

        }else{

            $image=$elder->image;
        }

        $elder->update([
            'name' => $request->name,
            'image' => $image,
            'email' =>$request->email,
            'tag_name' => $request->tag_name,
            'phone_number'=>$request->phone,
            'status'=>$request->status,
        ]);

        return $this->handelResponse('','The Elder has been updated successfully');
    }


    public function get_Articles($id){
        $ID=$this->getRealID(Elder::class, $id);
        $data_id  = Elder::with('Article')->findOrFail($ID);
        return new RelationArticlsElderResource($data_id);
    }

    public function Elders_Approve(){
        $data_elder = Elder::with('Audio')->where('status','Approve')->get();
        return EldersApproveResource::collection($data_elder)->resolve();

    }

    public function Delete(Request $request){

        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:elders,random_id',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Elder::class, $request->id);
        $elder =  Elder::find($ID)[0];

        if ($elder->image) {
            $this->fileRemove($elder->image);
        }
        $elder->delete();
        return $this->handelResponse('','The Elder  has been Deleted successfully');

    }
}
