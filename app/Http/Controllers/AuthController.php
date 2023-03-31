<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public static function login(Request $request) {
        $user = DB::table('users')->where('email', $request->email)->where('password', $request->password)->first();
        if ($user) {
            $access_token = Str::random(60);
            DB::table('users')->where('id', $user->id)->update(['access_token' => $access_token]);
            $user = DB::table('users')->find($user->id);
            return response()->json($user);
        } else {
            return response()->json(false);
        }
    }
}
