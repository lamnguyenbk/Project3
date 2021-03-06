@extends("layout.index")

@section("content")

<section class="container bg_container" style="margin-top: 80px;">
    <div id="main_text">
      <img src="../register48x48.png" />&nbsp;Sinh viên
    </div>
    <div id="hr">
    </div>
    <div class="">
      <h3 class="text-center">{{ $user->full_name }}</h3>
      @if(count($errors) > 0)
          <div class="alert alert-danger">
              @foreach($errors->all() as $err)
                  {{$err}}<br>
              @endforeach
          </div>
      @endif

      @if(session('thongbao'))
          <div class="alert alert-success">
              {{session('thongbao')}}
          </div>
      @endif
      <form action="student/profile/{{Auth::user()->username}}/edit" method='POST'>
      <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-8">
            <table class="table">
              <tr>
                <td class="col-sm-2">
                  <p>Họ tên</p>
                </td>
                <td class="col-sm-8">
                  <p>{{ $user->full_name }}</p>
                </td>
              </tr>
              <tr>
                <td class="col-sm-2">
                  <p>Lớp</p>
                </td>
                <td class="col-sm-8">
                  <p>{{ $user->student->class }}</p>
                </td>
              </tr>
              <tr>
                <td class="col-sm-2">
                  <p>Full name <font color="red"> *</font></p>
                </td>
                <td class="col-sm-8">
                  <input class="form-control" type="text" name="full_name" value="{{ $user->full_name }}" />
                </td>
              </tr>
              <tr>
                <td class="col-sm-2">
                  <p>Class</p>
                </td>
                <td class="col-sm-8">
                  <input class="form-control" type="text" name="class" value="{{ $user->student->class }}" />
                </td>
              </tr>
              <tr>
                <td class="col-sm-2">
                  <p>Emai<font color="red"> *</font></p>
                </td>
                <td class="col-sm-8">
                  <input class="form-control" type="text" name="email" value="{{ $user->email }}" />
                  
                </td>
               
              </tr>
              <tr>
                <td class="col-sm-2">
                  <p>Số điện thoại</p>
                </td>
                <td class="col-sm-8">
                  <input class="form-control" type="text" name="phone" value="{{ $user->phone }}" />
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="text-center">

          <button type='submit' class="btn btn-primary text-center">Chỉnh sửa</button>
        </div>
      </form>
    </div>
  </section>
@endsection