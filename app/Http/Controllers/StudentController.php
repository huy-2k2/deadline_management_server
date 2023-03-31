<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public static function getDeadlines(Request $request) {
        $data = DB::table('users')->join('classes_users', 'users.id', '=', 'classes_users.user_id')->join('deadlines', 'classes_users.class_id', '=', 'deadlines.class_id')->join('classes', 'classes_users.class_id', '=', 'classes.id')->where('users.id', $request->user_id)->get();
        return response()->json($data);
    }

    public static function getClasses(Request $request) {
        $classes = DB::table('users')->join('classes_users', 'users.id', '=', 'classes_users.user_id')->join('classes', 'classes.id', '=', 'classes_users.class_id')->where('users.id', $request->user_id)->get();
        return response()->json($classes);
    }

    public static function updateFreetimes(Request $request) {
        $user_id = $request->user_id;
        $freetimes = $request->freetimes;
        DB::table('freetimes')->where('user_id', $user_id)->delete();
        foreach($freetimes as $index => $freetime) {
            foreach($freetime as $time) {
                DB::table('freetimes')->insert(['index' => $index, 'user_id' => $user_id, 'start' => $time['start'], 'end' => $time['end']]);
            }
        }
        return response()->json(true);
    }

    public static function getFreetimes(Request $request) {
        $user_id = $request->user_id;
        $freetimes = DB::table('freetimes')->where('user_id', $user_id)->get();
        return response()->json($freetimes);
    }

}
