<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Student;
use App\GroupStudent;
use App\Group;
use App\Document;
use \DateTime;

class ProjectController extends Controller
{   

    public function getListProject(Request $request)
    {   
        if($request->ajax()){
            $output = '';
            $semester = $request['search'];
            $user = Auth::user();
            if(Auth::user()->position == 2) {
                $listProject  = DB::table('teacher')
                ->join('group', 'group.id_teacher', '=', 'teacher.id_teacher')
                ->join('user', 'user.username', '=', 'teacher.username')
                ->select('user.full_name', 'group.*')
                ->where('teacher.username', '=',  $user->username)
                ->where('group.semester', '=', $semester)->get();
            }
            if(Auth::user()->position == 1) {
                $listProject  = DB::table('student')->join('group_student', 'student.id_student', '=', 'group_student.id_student')
                ->join('group', 'group.id_group', '=', 'group_student.id_group')
                ->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
                ->join('user', 'user.username', '=', 'teacher.username')
                ->select('user.full_name', 'group.*')
                ->where('student.username', '=',  $user->username)
                ->where('group.semester', '=', $semester)->get();
            }

            $total_row = $listProject->count();
            if($total_row > 0){
                $view = view('pages.getListProject', compact('listProject'));
                return response($view);
            }
            else{
                $output = 'output';
                $data = array(
                'table_data'  => $output
                );
                return response($data);
            }
        }
    }

    public function getProjectDetail($id_group) {
        
        $project = DB::table('group')->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
            ->join('user', 'user.username', '=', 'teacher.username')
            ->select('group.*', 'user.full_name')
            ->where('group.id_group', '=', $id_group)->get();
        $semester = Group::find($id_group)->value('semester');
        $subject = Group::find($id_group)->value('id_subject');
        if($subject == 1) {
            $scheduel_contents  = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')
                ->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')
                ->select('content_sub_scheduel.*')
                ->where('subject_scheduel.semester', '=', $semester)
                ->where('subject.id_subject', '=', $subject)->orderBy('content_sub_scheduel.time_deadline', 'desc')
                ->first();
        } else {
            // $scheduel_contents = Group::find(1)->subject->subjectScheduel->content->;
            $scheduel_contents = DB::table('group')->join('group_scheduel', 'group.id_group', '=', 'group_scheduel.id_group')
                ->join('content_group_scheduel', 'group_scheduel.id_scheduel', '=', 'content_group_scheduel.id_scheduel')
                ->select('content_group_scheduel.*')
                ->where('group.id_group', '=', $id_group)->orderBy('content_group_scheduel.time_deadline', 'desc')
                ->first();
        }
        $now = new DateTime();
        $date = new DateTime($scheduel_contents->time_deadline);
        if($now > $date) {
            $group = Group::find($id_group); 
            $group->finish_project = 1;
            $group->save();
        } else {
            $group = Group::find($id_group); 
            $group->finish_project = 0;
            $group->save();
        }

        if(Auth::user()->position == 2) {
            return view('teacher.projectDetail', ['projects'=> $project]);
        }
        if(Auth::user()->position == 1) {
            return view('student.projectDetail', ['projects'=> $project]);
        }


    }
}
