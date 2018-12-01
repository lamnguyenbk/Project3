<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Student;

class AdStudentController extends Controller
{
    //
	public function getListStudent(){
		return view('admin.user.listStudent');
	}

	public function getUpdateStudent($id){
		$student = DB::table('user')->join('student', 'student.username', '=', 'user.username')->where('id_student','=', $id)->first();
		return view('admin.user.updateStudent', ['student'=>$student]);
	}

	public function postUpdateStudent(Request $request, $id_student){
		$this->validate($request, 
			[
				'username' => 'required|unique:user,username,'.$request->username.',username|min:3|max:45',
				'password' => 'required',
				'full_name' => 'required|min:3|max:45',
				'email' => 'email',
				'phone' => 'regex:/(0)[0-9]/|min:10|max:11'
			], 
			[
				'username.required' => 'Bạn chưa nhập Username',
				'username.unique' => 'Tài Khoản này đã tồn tại',
				'username.min' => 'Username cần có độ dài từ 3 đến 45 kí tự',
				'username.max' => 'Username cần có độ dài từ 3 đến 45 kí tự',

				'password.required' => 'Bạn chưa nhập password',

				'full_name.required' => 'Bạn chưa nhập Họ Tên',
				'full_name.min' => 'Họ Tên cần có độ dài từ 3 đến 45 kí tự',
				'full_name.max' => 'Họ Tên cần có độ dài từ 3 đến 45 kí tự',

				'email.email' => 'Bạn cần nhập đúng địa chỉ email',

				'phone.regex' => 'SĐT chỉ nên chứa chữ số và bắt đầu bằng 0',
				'phone.min' => 'SĐT cần có độ dài 10 hoặc 11 số',
				'phone.max' => 'SĐT cần có độ dài 10 hoặc 11 số'
			]);

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		$student = Student::find($id_student);
		$user = User::find($student->username);
		$user->username = $request->username;
		$user->password = $request->password;
		$user->full_name = $request->full_name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$student->username = $request->username;
		$student->class = $request->class;
		$student->save();
		$user->save();
		

		return redirect('admin/user/listStudent')->with('thongbao','Bạn đã sửa thành công');
	}

	public function getDeleteStudent($id){
		$student = Student::find($id);
		$user = User::find($student->username);

		
		$user->delete();

		return redirect('admin/user/listStudent')->with('thongbao','Bạn đã xóa thành công');
	}

	public function postAddStudent(Request $request){
		$this->validate($request, 
			[
				'username' => 'required|unique:user,username|unique:student,username|min:3|max:45',
				'password' => 'required',
				'full_name' => 'required|min:3|max:45',
				'email' => 'email',
				'phone' => 'regex:/(0)[0-9]/|min:10|max:11'
			], 
			[
				'username.required' => 'Bạn chưa nhập Username',
				'username.unique' => 'Tài Khoản này đã tồn tại',
				'username.min' => 'Username cần có độ dài từ 3 đến 45 kí tự',
				'username.max' => 'Username cần có độ dài từ 3 đến 45 kí tự',

				'password.required' => 'Bạn chưa nhập password',

				'full_name.required' => 'Bạn chưa nhập Họ Tên',
				'full_name.min' => 'Họ Tên cần có độ dài từ 3 đến 45 kí tự',
				'full_name.max' => 'Họ Tên cần có độ dài từ 3 đến 45 kí tự',

				'email.email' => 'Bạn cần nhập đúng địa chỉ email',

				'phone.regex' => 'SĐT chỉ nên chứa chữ số và bắt đầu bằng 0',
				'phone.min' => 'SĐT cần có độ dài 10 hoặc 11 số',
				'phone.max' => 'SĐT cần có độ dài 10 hoặc 11 số'
			]);

		
		$user = new User;
		$user->username = $request->username;
		$user->password = $request->password;
		$user->position = 2;
		$user->full_name = $request->full_name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$student = new Student;
		$student->username = $request->username;
		$student->class = $request->class;

		$user->save();
		$student->save();

		return redirect(url('admin/user/listStudent'))->with('thongbao','Bạn đã thêm thành công');

	}

	public function postSearch(Request $request){
		if($request->ajax()){
			$output = '';
			$query = $request->get('query');
			if($query != ''){
				$data = DB::table('user')
				->join('student', 'student.username', '=', 'user.username')
				->where(function($q) use ($query){
					$q->where('user.username', 'like', '%'.$query.'%')
					->orwhere('user.full_name', 'like', '%'.$query.'%')
					->orwhere('user.email', 'like', '%'.$query.'%')
					->orwhere('user.phone', 'like', '%'.$query.'%')
					->orwhere('student.class', 'like', '%'.$query.'%');
				})
				->get();  

			}
			else{
				$data = DB::table('user')
				->join('student', 'student.username', '=', 'user.username')
				->get();
			}

			$total_row = $data->count();
			if($total_row > 0){
				foreach($data as $row){
					$output .= '
					<tr>
					<td>'.$row->id_student.'</td>
					<td>'.$row->username.'</td>
					<td>'.$row->full_name.'</td>
					<td>'.$row->email.'</td>
					<td>'.$row->phone.'</td>
					<td>'.$row->class.'</td>
					<td><button class="btn btn-default" name="add" onclick="location.href=\'admin/user/updateStudent/'.$row->id_student.'\'">Sửa</button></td>
					<td><button class="btn btn-default" name="delete" onclick="location.href=\'admin/user/deleteStudent/'.$row->id_student.'\'">Xóa</button></td>
					</tr>
					';
				}
			}
			else{
				$output = '
				<tr>
				<td align="center" colspan="5">No Data Found</td>
				</tr>
				';
			}
			$data = array(
				'table_data'  => $output
			);

			echo json_encode($data);
		}
	}

}
