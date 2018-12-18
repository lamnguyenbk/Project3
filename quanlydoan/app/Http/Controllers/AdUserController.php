<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Student;

class AdUserController extends Controller
{
    //
  public function getListAdmin(Request $request){
    return view('admin.user.listAdmin');
  }
  

  public function getUpdateAdmin($username){
   $admin = User::find($username);
   return view('admin.user.updateAdmin', ['admin'=>$admin]);
 }

 public function postUpdateAdmin(Request $request, $username){
   $this->validate($request, 
     [
      'username' => 'required|unique:user,username,'.$username.',username|min:3|max:45',
      'full_name' => 'required|min:3|max:45',
      'email' => 'email',
      'phone' => 'nullable|regex:/(0)[0-9]/|min:10|max:11'
    ], 
    [
      'username.required' => 'Bạn chưa nhập Username',
      'username.unique' => 'Tài Khoản này đã tồn tại',
      'username.min' => 'Username cần có độ dài từ 3 đến 45 kí tự',
      'username.max' => 'Username cần có độ dài từ 3 đến 45 kí tự',

      'full_name.required' => 'Bạn chưa nhập Họ Tên',
      'full_name.min' => 'Họ Tên cần có độ dài từ 3 đến 45 kí tự',
      'full_name.max' => 'Họ Tên cần có độ dài từ 3 đến 45 kí tự',

      'email.email' => 'Bạn cần nhập đúng địa chỉ email',

      'phone.regex' => 'SĐT chỉ nên chứa chữ số và bắt đầu bằng 0',
      'phone.min' => 'SĐT cần có độ dài 10 hoặc 11 số',
      'phone.max' => 'SĐT cần có độ dài 10 hoặc 11 số'
    ]);

   $admin = User::find($username);
   $admin->username = $request->username;
   $admin->position = 3;
   $admin->full_name = $request->full_name;
   $admin->email = $request->email;
   $admin->phone = $request->phone;
   $admin->save();

   return redirect('admin/user/listAdmin')->with('thongbao','Bạn đã sửa thành công');
 }

 public function getDeleteAdmin($username){
   $admin = User::find($username);
   $admin->delete();

   return redirect('admin/user/listAdmin')->with('thongbao','Bạn đã xóa thành công');
 }

 public function postAddAdmin(Request $request){
   $this->validate($request, 
     [
      'username' => 'required|unique:user,username|min:3|max:45',
      'password' => 'required',
      'full_name' => 'required|min:3|max:45',
      'email' => 'email',
      'phone' => 'nullable|regex:/(0)[0-9]/|min:10|max:11'
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

   $admin = new User;
   $admin->username = $request->username;
   $admin->password = bcrypt($request->password);
   $admin->position = 3;
   $admin->full_name = $request->full_name;
   $admin->email = $request->email;
   $admin->phone = $request->phone;
   $admin->save();

   return redirect(url('admin/user/listAdmin'))->with('thongbao','Bạn đã thêm thành công');

 }

 public function postSearch(Request $request){
  if($request->ajax()){
    $query = $request['search'];
    $data = $this->search($query);
    $view = view('admin.user.getListAdmin', compact('data'))->render();
    return response($view);

  }
}

public function getSearchPagination(Request $request){
    if($request->ajax()){
      $query = $request['search'];
      $data = $this->search($query);
      return view('admin.user.getListAdmin', compact('data'))->render();
    }
  }

public function search($query){
  if($query != ''){
    return $data = DB::table('user')
    ->where('position', '=', '3')
    ->where(function($q) use ($query){
      $q->where('username', 'like', '%'.$query.'%')
      ->orwhere('full_name', 'like', '%'.$query.'%')
      ->orwhere('email', 'like', '%'.$query.'%')
      ->orwhere('phone', 'like', '%'.$query.'%');
    })
    ->paginate(5);  

  }
  else{
    return $data = DB::table('user')
    ->where('position', '=', '3')
    ->paginate(5);
  }
}

}
