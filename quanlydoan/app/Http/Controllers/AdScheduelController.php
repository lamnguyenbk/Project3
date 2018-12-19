<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\SubjectScheduel;
USE App\ContentSubjectScheduel;
use Illuminate\Support\Facades\DB;

class AdScheduelController extends Controller
{
    //

    public function getAdScheduel(){
        return view('admin.scheduel.scheduel');
    }

    public function postSearch(Request $request){
    	if($request->ajax()){
			$semester = $request['semester'];
			$data = $this->search($semester);
			$view = view('admin.scheduel.getScheduel', compact('data'))->render();
			return response($view);
		}
    }

    public function search($semester){
    	return $data = DB::table('subject_scheduel')->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')->where('subject_scheduel.semester', '=', $semester)->orderBy('time_deadline')->get();
    }

    public function postAdAddScheduel(Request $request, $id_subject){
        

        $this->validate($request, 
            [
            'require' => 'required|regex:/(^[A-Za-z0-9]+$)+/|unique:content_sub_scheduel,require,'.$request->require.',require|min:3',
            'descript' => 'required|min:3',
            'time_deadline' => 'required'
            ], 
            [
            'require.required' => 'Bạn chưa nhập Mã Tài Liệu',
            'require.regex' => 'Mã Tài Liệu chỉ được nhập chữ, số',
            'require.min' => 'Mã Tài Liệu cần có độ dài từ 3 kí tự trở nên',

            'descript.required' => 'Bạn chưa nhập Nội dung mô tả yêu cầu',
            'descript.min' => 'Nội dung tiêu chí cần có độ dài từ 3 kí tự trở nên',

            'time_deadline.required' => 'Bạn chưa nhập thời hạn nộp '
            ]);

        $semester = $request->semester;

        $subject_scheduel = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')->where('subject_scheduel.semester', '=', $semester)->get();
        
        $countScheduel = count($subject_scheduel);
        if($countScheduel > 0){
            $contentSubjectScheduel = new ContentSubjectScheduel();
            $contentSubjectScheduel->id_subject_scheduel = $subject_scheduel[0]->id_subject_scheduel;
            $contentSubjectScheduel->require = $request->require;
            $contentSubjectScheduel->time_deadline = $request->time_deadline;
            $contentSubjectScheduel->descript = $request->descript;
            $contentSubjectScheduel->penalty = $request->penalty;
            $contentSubjectScheduel->save();

            return redirect(url('admin/scheduel/show/1'))->with('thongbao','Bạn đã thêm thành công');
        }else{
        	$subject_scheduel = new SubjectScheduel();
        	$subject_scheduel->id_subject = 1;
        	$subject_scheduel->semester = $semester;
        	$subject_scheduel->save();

        	$contentSubjectScheduel = new ContentSubjectScheduel();
            $contentSubjectScheduel->id_subject_scheduel = $subject_scheduel->id_subject_scheduel;
            $contentSubjectScheduel->require = $request->require;
            $contentSubjectScheduel->time_deadline = $request->time_deadline;
            $contentSubjectScheduel->descript = $request->descript;
            $contentSubjectScheduel->penalty = $request->penalty;
            $contentSubjectScheduel->save();

            return redirect(url('admin/scheduel/show/1'))->with('thongbao','Bạn đã thêm thành công');
        }
    }

    public function getUpdateScheduelContent(Request $request, $id_subject, $id_subject_scheduel, $id_content_sub_scheduel){
    	$content_sub_scheduel = DB::table('content_sub_scheduel')->join('subject_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')->where('id_subject','=', $id_subject)->where('content_sub_scheduel.id_subject_scheduel', '=', $id_subject_scheduel)->where('content_sub_scheduel.id_content_scheduel', '=', $id_content_sub_scheduel)->first();
		return view('admin.scheduel.updateScheduel', ['content_sub_scheduel'=>$content_sub_scheduel]);
    }

    public function postUpdateScheduelContent(Request $request, $id_subject, $id_subject_scheduel, $id_content_sub_scheduel){
    	$this->validate($request, 
            [
            'require' => 'required|regex:/(^[A-Za-z0-9]+$)+/|unique:content_sub_scheduel,require,'.$request->require.',require|min:3',
            'descript' => 'required|min:3',
            'time_deadline' => 'required'
            ], 
            [
            'require.required' => 'Bạn chưa nhập Mã Tài Liệu',
            'require.regex' => 'Mã Tài Liệu chỉ được nhập chữ, số',
            'require.min' => 'Mã Tài Liệu cần có độ dài từ 3 kí tự trở nên',

            'descript.required' => 'Bạn chưa nhập Nội dung mô tả yêu cầu',
            'descript.min' => 'Nội dung tiêu chí cần có độ dài từ 3 kí tự trở nên',

            'time_deadline.required' => 'Bạn chưa nhập thời hạn nộp '
            ]);


        DB::statement('SET FOREIGN_KEY_CHECKS=0');
		$content_sub_scheduel = ContentSubjectScheduel::find($id_content_sub_scheduel);
		$content_sub_scheduel->id_subject_scheduel =  $id_subject_scheduel;
		$content_sub_scheduel->require = $request->require;
		$content_sub_scheduel->time_deadline = $request->time_deadline;
		$content_sub_scheduel->descript = $request->descript;
		$content_sub_scheduel->penalty = $request->penalty;
		$content_sub_scheduel->save();
	
		return redirect('admin/scheduel/show/1')->with('thongbao','Bạn đã sửa thành công');
    }

    public function getDeleteScheduel(Request $request, $id_subject, $id_subject_scheduel, $id_content_sub_scheduel){
		$content_sub_scheduel = ContentSubjectScheduel::find($id_content_sub_scheduel);
		$content_sub_scheduel->delete();

		return redirect('admin/scheduel/show/1')->with('thongbao','Bạn đã xóa thành công');
	}

}
