<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\AdminRegisterRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating Admin for the application and
    | redirecting to Dashboard.
    */

        // Create Admin Accounts
    public function register(AdminRegisterRequest $request)
    {
        // Validate Admin Register
        $data=$request->validated();
        $result=Admin::create($data);
        if($result){
            return $this->handleResponse('','Admin Registerd Successfully');
        }
        return $this->handelError('','Something Went Wrong Please Try Again Later');
    }

        // Admin Login to Dashboard
    public function login (AdminLoginRequest $request)
    {
        // Validate Admin Register
        $data=$request->validated();
        if(auth('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
           $admin= auth('admin')->user();
           $response = [
            'name' => $admin->name,
            'token' => $admin->createToken($admin->email)->plainTextToken,
            ];
            return $this->handelResponse($response,'Admin Logged in Successfully');

        }
        return $this->handelError('','Something Went Wrong Please Try Again Later');



    }
    // public function Update(Request $request){
    //     $validate = Validator::make($request->all(), [
    //         'id'=> 'required|integer|exists:admins,id',
    //         'name' => 'required',
    //         'email' => 'required|email|unique:admins,email',
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json($validate->errors());
    //     }
    //     Admin::find($request->id)->Update($request->all());

    // }
    public function Get( ){
         $data= Admin::all();
        return AdminResource::collection($data)->resolve();
    }
    public function Delete(Request $request ){
        $validate = Validator::make($request->all(), [
            'id'=> 'required|integer|exists:admins,id',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        Admin::find($request->id)->delete();
        return $this->handelResponse('','The admin has been successfully deleted');

    }
}
