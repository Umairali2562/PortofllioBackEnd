<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    function index(){
        return view('admin.login');
    }
    function login(Request $request){
        if(Auth::attempt($request->only('email','password'))){
            return redirect('/admin');
        }else{
            return redirect('/login');
        }

    }
    function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
