<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\NotifyEmail;
use App\Events\createDeadlineEvent;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendNotifyEmail;


class TeacherController extends Controller
{
    public static function getClasses(Request $request) {
        $classes =  DB::table('classes')->get();
        $result = [];
        foreach($classes as $class) {
            if($class->user_id == $request->user_id)
                $result[] = $class;
        }
        return response()->json($result);
    }
    public static function getClassDetail(Request $request) {
        $class_id = $request->class_id;
        $student_ids = DB::table('classes_users')->where('class_id', $class_id)->get();
        $deadlines = DB::table('deadlines')->where('class_id', $class_id)->orderBy('due_at', 'DESC')->get();
        $students = [];
        foreach($student_ids as $student_id) {
            $students[] = DB::table('users')->find($student_id->user_id);
        }
        return response()->json(['students' => $students, 'deadlines' => $deadlines]);
    }
    public static function createDealine(Request $request) {
        $due_at = $request->date;
        $class_id = $request->class_id;
        $des = $request->description;
        DB::table('deadlines')->insert(['due_at' => $due_at, 'class_id' => $class_id, 'description' => $des]);
        $deadlines = DB::table('deadlines')->where('class_id', $class_id)->orderBy('due_at', 'DESC')->get();
        // $students = DB::table('users')->join('classes_users', 'users.id', '=', 'classes_users.user_id')->where('classes_users.class_id', $class_id)->get();
        $class = DB::table('classes')->find($class_id);
        event(new createDeadlineEvent(['class' => $class, 'due_at' => $due_at, 'description' => $des]));
        return response()->json($deadlines);
    }
}
