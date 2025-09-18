<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function register(Request $e)
    {
        $user = new User;
        $user->name = $e->input('name');
        $user->email = $e->input('email');
        //$user->password = $e->input('password');
        $user->password = Hash::make($e->input('password'));
        $user->save();
        return $user;
    }

    function login(Request $e)
    {
       $User=User::where('email',$e->email)->first();
        if ($User && Hash::check($e->input('password'), $User->password)) {
            return $User;
        } else {
            //return $User;
            return "Sorry, invalid credentials";
        }
    }
}
