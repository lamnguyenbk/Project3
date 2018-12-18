<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdScheduelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use \DateTime;
use App\User;
use App\GroupScheduel;
use App\ContentGroupScheduel;
use App\Group;
use App\Subject;
use App\SubjectScheduel;
USE App\ContentSubjectScheduel;

class ScheduelController extends Controller
{   

    public function getScheduel($id_group) {
        $semester = Group::find($id_group)->semester;
        $subject = Group::find($id_group)->id_subject;

        // $scheduel_contents = Group::find(1)->subject->subjectScheduel->content->;
        $scheduel_contents = DB::table('group')->join('group_scheduel', 'group.id_group', '=', 'group_scheduel.id_group')
                ->join('content_group_scheduel', 'group_scheduel.id_scheduel', '=', 'content_group_scheduel.id_scheduel')
                ->select('content_group_scheduel.*')
                ->where('group.id_group', '=', $id_group)->orderBy('content_group_scheduel.time_deadline', 'asc')
                ->get();

        $scheduel_subject_contents = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')
                ->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')
                ->select('content_sub_scheduel.*')
                ->where('subject_scheduel.semester', '=', $semester)
                ->where('subject.id_subject', '=', $subject)->orderBy('content_sub_scheduel.time_deadline', 'asc')
                ->get();

        $project = DB::table('group')->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
                ->join('user', 'user.username', '=', 'teacher.username')
                ->select('group.*', 'user.full_name')
                ->where('group.id_group', '=', $id_group)->get(); 
        if(Auth::user()->position == 2) {
            return view('teacher.scheduel', ['scheduel_contents' => $scheduel_contents, 'scheduel_subject_contents' => $scheduel_subject_contents, 'projects' => $project]);
        }
        if(Auth::user()->position == 1) {
            return view('student.scheduel', ['scheduel_contents' => $scheduel_contents, 'scheduel_subject_contents' => $scheduel_subject_contents, 'projects' => $project]);
        }
        
    }

    public function addScheduelContent(Request $request, $id_group) {

        $validator = \Validator::make($request->all(), 
            [
            'require' => 'required|unique:content_group_scheduel,require,'.$request->require.',require',
            'descript' => 'required|min:3',
            'deadline' => 'required'
            ], 
            [
            'require.required' => 'Bạn chưa nhập Mã tài liệu',

            'descript.required' => 'Bạn chưa nhập Nội dung mô tả yêu cầu',
            'descript.min' => 'Nội dung mô tả yêu cầu cần có độ dài từ 3 kí tự trở nên',

            'deadline.required' => 'Bạn chưa nhập thời hạn nộp '
            ]
        );

        if ($validator->fails()) {
            $data = array(
                "error" => $validator->errors()->all()
            );
            return response($data);
        }
        else {
            //lấy ra kì học và môn học tương ứng với nhóm
            $semester = Group::find($id_group)->semester;
            $subject = Group::find($id_group)->id_subject;

            //lấy ra nội dung lịch trình của bộ môn theo kỳ học và môn
            $scheduel_subject_contents = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')
                ->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')
                ->select('content_sub_scheduel.*')
                ->where('subject_scheduel.semester', '=', $semester)
                ->where('subject.id_subject', '=', $subject)
                ->where('content_sub_scheduel.require', '=', $request->require)
                ->get();

            //kiểm tra nội dung lịch trình tương ứng đã được bộ môn đưa ra
            $total = $scheduel_subject_contents->count();
            foreach ($scheduel_subject_contents as $value) {
                    $scheduel_subject_content = $value->time_deadline;
                }
            // nội dung lịch trình đã được bộ môn quy định
            if($total > 0) {

                $date_input = new DateTime($request->deadline);
                $date_compare = new DateTime($scheduel_subject_content);
                // nếu nội dung lịch trình nhập vào không vi phạm về thời gian so với lịch trình bộ môn đưa ra
                if($date_input < $date_compare) {
                    //kiểm tra lịch trình cho môn đã được tạo chưa
                    $id_scheduel = DB::table('group_scheduel')->select('id_scheduel')->where('id_group', '=', $id_group)
                                ->get();
                    $total = $id_scheduel->count();
                    foreach ($id_scheduel as $value) {
                        $id = $value->id_scheduel;
                    }
                    //đã tạo nội dung lịch trình
                    if($total > 0) {
                        $scheduelContent = new ContentGroupScheduel();
                        $scheduelContent->id_scheduel = $id;
                        $scheduelContent->time_deadline = $request->deadline;
                        $scheduelContent->require = $request->require;
                        $scheduelContent->descript = $request->descript;
                        $scheduelContent->penalty = $request->penalty;
                        $scheduelContent->save();
                        
                        $data = array(
                            "output" => $scheduelContent->id_content
                        );
                        return response($data);
                        
                    }
                    //chưa tạo nội dung nào tạo thêm bản ghi ở bảng group_scheduel
                    else {

                        $scheduel = new GroupScheduel();
                        $scheduel->id_group = $id_group;
                        $scheduel->save();
                        $id_scheduel = DB::table('group_scheduel')->select('id_scheduel')->where('id_group', '=', $id_group)->get();

                        foreach ($id_scheduel as $value) {
                            $id = $value->id_scheduel;
                        }

                        $scheduelContent = new ContentGroupScheduel();
                        $scheduelContent->id_scheduel = $id;
                        $scheduelContent->time_deadline = $request->deadline;
                        $scheduelContent->require = $request->require;
                        $scheduelContent->descript = $request->descript;
                        $scheduelContent->penalty = $request->penalty;
                        $scheduelContent->save();
                        
                        $data = array(
                            "output" => $scheduelContent->id_content
                        );
                        return response($data);
                    }
                } 
                else {
                    $error = "error";
                    $data = array(
                        "error_message" => $error
                    );
                    return response($data);
                }

            }
            // nội dung lịch trình chưa được bộ môn quy định
            else {
                //kiểm tra lịch trình cho môn đã được tạo chưa
                $id_scheduel = DB::table('group_scheduel')->select('id_scheduel')->where('id_group', '=', $id_group)
                ->get();
                $total = $id_scheduel->count();
                foreach ($id_scheduel as $value) {
                    $id = $value->id_scheduel;
                }
                //đã tạo nội dung lịch trình
                if($total > 0) {
                    $scheduelContent = new ContentGroupScheduel();
                    $scheduelContent->id_scheduel = $id;
                    $scheduelContent->time_deadline = $request->deadline;
                    $scheduelContent->require = $request->require;
                    $scheduelContent->descript = $request->descript;
                    $scheduelContent->penalty = $request->penalty;
                    $scheduelContent->save();
                    
                    $data = array(
                        "output" => $scheduelContent->id_content
                    );
                    return response($data);
                    
                }
                //chưa tạo nội dung nào tạo thêm bản ghi ở bảng group_scheduel
                else {

                    $scheduel = new GroupScheduel();
                    $scheduel->id_group = $id_group;
                    $scheduel->save();
                    $id_scheduel = DB::table('group_scheduel')->select('id_scheduel')->where('id_group', '=', $id_group)->get();

                    foreach ($id_scheduel as $value) {
                        $id = $value->id_scheduel;
                    }

                    $scheduelContent = new ContentGroupScheduel();
                    $scheduelContent->id_scheduel = $id;
                    $scheduelContent->time_deadline = $request->deadline;
                    $scheduelContent->require = $request->require;
                    $scheduelContent->descript = $request->descript;
                    $scheduelContent->penalty = $request->penalty;
                    $scheduelContent->save();
                    
                    $data = array(
                        "output" => $scheduelContent->id_content
                    );
                    return response($data);
                }
            }
        }
    }

    public function delScheduelContent(Request $request, $id_group) {
        $content = ContentGroupScheduel::find($request->id);
        $content->delete();  
        $id = $request->id;   
        return response($id);
    }

    public function getUpdateScheduelContent($id_group, $id_content) {
        $project = DB::table('group')->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
                ->join('user', 'user.username', '=', 'teacher.username')
                ->select('group.*', 'user.full_name')
                ->where('group.id_group', '=', $id_group)->get();
        $content = ContentGroupScheduel::find($id_content); 
        return view('teacher/updateScheduel', ['content' => $content, 'projects' => $project]);
    }

    public function postUpdateScheduelContent(Request $request, $id_group, $id_content) {
        $validator = \Validator::make($request->all(), 
            [
            'require' => 'required|unique:content_group_scheduel,require,'.$request->require.',require',
            'descript' => 'required|min:3',
            'time_deadline' => 'required',
            'penalty' =>  'required'
            ], 
            [
            'require.required' => 'Bạn chưa nhập Mã tài liệu',

            'descript.required' => 'Bạn chưa nhập Nội dung mô tả yêu cầu',
            'descript.min' => 'Nội dung mô tả yêu cầu cần có độ dài từ 3 kí tự trở nên',

            'time_deadline.required' => 'Bạn chưa nhập thời hạn nộp ',
            'penalty.required' => 'Bạn chưa nhập điểm trừ'
            ]
        );
        if ($validator->fails()) {
            return redirect('teacher/project/'.$id_group.'/scheduel/update/'.$id_content)->with('errors', $validator->errors());
        } else {
            $content = ContentGroupScheduel::find($id_content); 
            $content->time_deadline = $request->time_deadline;
            $content->require = $request->require;
            $content->descript = $request->descript;
            $content->penalty = $request->penalty;
            $content->save();

            return redirect('teacher/project/'.$id_group.'/scheduel')->with('thongbao','Bạn đã sửa thành công');
        }
    }

    


}