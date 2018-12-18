<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use App\User;
use App\EvalutionCriteria;
use App\Group;
use \DateTime;

class EvaluationController extends Controller
{   

    public function getEvaluation($id_group) {
        $evaluations = DB::table('group')->join('evalution_criteria', 'group.id_group', '=', 'evalution_criteria.id_group')
                ->select('evalution_criteria.*')
                ->where('group.id_group', '=', $id_group)
                ->get();
        $project = DB::table('group')->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
                ->join('user', 'user.username', '=', 'teacher.username')
                ->select('group.*', 'user.full_name')
                ->where('group.id_group', '=', $id_group)->get(); 
        
        $point = $this->evalue($id_group);
                
        if(Auth::user()->position == 2) {
            return view('teacher.evaluate', ['evaluations' => $evaluations, 'projects' => $project, 'point' => $point]);
        }
        if(Auth::user()->position == 1) {
            return view('student.evaluate', ['evaluations' => $evaluations, 'projects' => $project, 'point' => $point]);
        }
    }

    public function addEvaluation(Request $request, $id_group) {

        $validator = \Validator::make($request->all(), 
            [
            'content' => 'required|min:3|max:2000',
            'bonus' => 'required|min:0|max:10'
            ], 
            [
            'content.required' => 'Bạn chưa nhập Nội dung tiêu chí',
            'content.min' => 'Nội dung tiêu chí cần có độ dài từ 3 đến 2000 kí tự',
            'content.max' => 'Nội dung tiêu chí cần có độ dài từ 3 đến 2000 kí tự',

            'bonus.required' => 'Bạn chưa nhập điểm ']
        );

        if ($validator->fails())
        {
            $data = array(
                "error" => $validator->errors()->all()
            );
            return response($data);
        }
        else {
            $evaluations = new EvalutionCriteria();
            $evaluations->id_group = $id_group;
            $evaluations->content = $request->content;
            $evaluations->bonus = $request->bonus;
            $evaluations->save();
            
            $data = array(
                "output" => $evaluations->id_evalution_criteria
            );
            return response($data);
        }
    }       

    public function delEvaluation(Request $request, $id_group) {
        $content = EvalutionCriteria::find($request->id);
        $content->delete(); 
        $id = $request->id;  
        return response($id); 
    }

    public function getUpdateEvaluation($id_group, $id_evalution_criteria) {
        $project = DB::table('group')->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
                ->join('user', 'user.username', '=', 'teacher.username')
                ->select('group.*', 'user.full_name')
                ->where('group.id_group', '=', $id_group)->get();
        $content = EvalutionCriteria::find($id_evalution_criteria); 
        return view('teacher/updateEvaluate', ['content' => $content, 'projects' => $project]);
    }

    public function postUpdateEvaluation(Request $request, $id_group, $id_evalution_criteria) {
        $validator = \Validator::make($request->all(), 
            [
            'content' => 'required|min:3|max:2000',
            'bonus' => 'required|min:0|max:10'
            ], 
            [
            'content.required' => 'Bạn chưa nhập Nội dung tiêu chí',
            'content.min' => 'Nội dung tiêu chí cần có độ dài từ 3 đến 2000 kí tự',
            'content.max' => 'Nội dung tiêu chí cần có độ dài từ 3 đến 2000 kí tự',

            'bonus.required' => 'Bạn chưa nhập điểm ']
        );

        if ($validator->fails())
        {
            return redirect('teacher/project/'.$id_group.'/evaluation/update/'.$id_evalution_criteria)->with('errors', $validator->errors());
        }
        else {
            $content = EvalutionCriteria::find($id_evalution_criteria); 
            $content->content = $request->content;
            $content->bonus = $request->bonus;
            $content->save();
            return redirect('teacher/project/'.$id_group.'/evaluation')->with('thongbao','Bạn đã sửa thành công!');
        }
    }

    public function evalue($id_group) {
        $semester = Group::find($id_group)->semester;
        $subject_id = Group::find($id_group)->id_subject;
        if($subject_id > 1) {
            $scheduel_contents = DB::table('group')->join('group_scheduel', 'group.id_group', '=', 'group_scheduel.id_group')
                ->join('content_group_scheduel', 'group_scheduel.id_scheduel', '=', 'content_group_scheduel.id_scheduel')
                ->select('content_group_scheduel.require', 'content_group_scheduel.time_deadline', 'content_group_scheduel.penalty')
                ->where('group.id_group', '=', $id_group)
                ->get();
            $total_require = $scheduel_contents->count();
            }
        else {
            $scheduel_content = DB::table('group')->join('group_scheduel', 'group.id_group', '=', 'group_scheduel.id_group')
                ->join('content_group_scheduel', 'group_scheduel.id_scheduel', '=', 'content_group_scheduel.id_scheduel')
                ->select('content_group_scheduel.require', 'content_group_scheduel.time_deadline', 'content_group_scheduel.penalty')
                ->where('group.id_group', '=', $id_group);

            $scheduel_contents = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')
                ->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')
                ->select('content_sub_scheduel.require', 'content_sub_scheduel.time_deadline', 'content_sub_scheduel.penalty')
                ->where('subject_scheduel.semester', '=', $semester)
                ->where('subject.id_subject', '=', $subject_id)
                ->union($scheduel_content)
                ->get();

            $total_scheduel_content = DB::table('group')->join('group_scheduel', 'group.id_group', '=', 'group_scheduel.id_group')
                ->join('content_group_scheduel', 'group_scheduel.id_scheduel', '=', 'content_group_scheduel.id_scheduel')
                ->select('content_group_scheduel.require')
                ->where('group.id_group', '=', $id_group);

            $total_scheduel_contents = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')
                ->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')
                ->select('content_sub_scheduel.require')
                ->where('subject_scheduel.semester', '=', $semester)
                ->where('subject.id_subject', '=', $subject_id)
                ->union($total_scheduel_content)
                ->get();
            $total_require = $total_scheduel_contents->count();
        }
        $studentDocuments = DB::table('group')
                        ->join('document', 'group.id_group', '=', 'document.id_group')
                        ->join('user','document.user_upload','=','user.username')
                        ->select('document.*')
                        ->where('group.id_group', '=', $id_group)
                        ->where('user.position','=',1)
                        ->get();
        $total_document = $studentDocuments->count();
        $bonus = DB::table('group')->join('evalution_criteria', 'group.id_group', '=', 'evalution_criteria.id_group')->where('evalution_criteria.id_group', '=', $id_group)->get();
        $point = 0;
        $bonus_point = 0;
        foreach ($studentDocuments as $value) {
            foreach ($scheduel_contents as $value1) {
                $match_require = DB::table('group')
                        ->join('document', 'group.id_group', '=', 'document.id_group')
                        ->join('user','document.user_upload','=','user.username')
                        ->select('document.*')
                        ->where('group.id_group', '=', $id_group)
                        ->where('user.position','=',1)
                        ->where('document.type','=',$value1->require)
                        ->get();
                        
                $total_require_match = $match_require->count();
                echo($total_require_match);
                if($value->type == $value1->require) {
                    $date_up = new DateTime($value->created_at);
                    $date_deadline = new DateTime($value1->time_deadline);
                    if($date_up > $date_deadline) {
                        $value->evaluate = ($value->evaluate - $value1->penalty) / $total_require_match;
                    }
                    else {
                         $value->evaluate = $value->evaluate / $total_require_match;
                    }
                }
            }
            $point += $value->evaluate; 
        }

        foreach ($bonus as  $value) {
            $bonus_point += $value->bonus;
        }

        if($total_require != 0) {
            $point_avg = $point / $total_require;
            $point_avg += $bonus_point;
            if($point_avg > 0){
                return $point_avg;
            }else{
                return 0;
            }
        } else if($total_document != 0 ){
            return $point / $total_document + $bonus_point;
        } else {
            return 0;
        }
    }  
}