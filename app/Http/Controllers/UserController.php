<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AudiofavirateUserResource;
use App\Http\Resources\AudioResource;
use App\Http\Resources\AudioUserResource;
use App\Http\Resources\ElderResource;
use App\Http\Resources\IdBookResource;
use App\Http\Resources\ImagefavirateUserResource;
use App\Http\Resources\imageResource;
use App\Http\Resources\userCommentResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Audio;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\RandomIDTrait;


class UserController extends Controller
{
    use RandomIDTrait;

    // create Rig USer

    public function Register(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'phonenumber' => $request->phonenumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),


        ]);
        $response['token'] = $user->createToken($user->email)->plainTextToken;
        $response['email'] = $user->email;
        return $this->handelResponse($response,'register successfully');
    }
    // create Login USer
    public function Login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $response = [
                'name' => $user->name,
                'token' => $user->createToken($user->email)->plainTextToken,
            ];
            return $this->handelResponse($response,'register successfully');
        } else {
            return response()->json(['error' => 'unauthorised']);
        }
    }

    // get user id
    public function get_id(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:users',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(User::class, $request->id);
        $data =  User::findOrFail($ID);

      return UserResource::make($data[0])->resolve();
    }
    //getAll User
    public function get_user_all()
    {
        $data = User::all();
        return UserResource::collection($data);
    }


    public function Get_id_favirate_audio($id){

        $get_id = User::with('Audios')->find($id);

        return AudiofavirateUserResource::collection($get_id->audio)->resolve();

    }

    public function Get_id_favirate_image($id){

         $get_id = User::with('images')->find($id);

        return imageResource::collection($get_id->image)->resolve();

    }

     public function Get_id_favirate_Books($id){

         $get_id = User::with('books')->find($id);

        return IdBookResource::collection($get_id->image)->resolve();

    }
     public function Get_id_favirate_ELder($id){

         $get_id = User::with('elders')->find($id);

        return ElderResource::collection($get_id->image)->resolve();

    }
    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        auth()->logout();
        return response(['success' => true, 'massega'=>'user logged out']);
    }


    public function ProfileUSer()
    {
        return new UserProfileResource(auth('sanctum')->user());
    }
}
