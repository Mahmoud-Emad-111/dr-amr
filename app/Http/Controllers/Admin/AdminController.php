<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\AdminRegisterRequest;
use App\Models\Admin;

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
        if(auth('admin')->attempt($data)){
            auth('admin')->login;
            return $this->handelResponse('','Admin Logged in Successfully');
        }
            return $this->handelError('','Something Went Wrong Please Try Again Later');
    }
}
