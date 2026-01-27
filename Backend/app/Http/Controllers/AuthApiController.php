<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthApiController extends Controller
{
    public function me(){
        $user = DB::table("users")->select("name","email")->find(1);
        return response()->json($user);
    }
}
