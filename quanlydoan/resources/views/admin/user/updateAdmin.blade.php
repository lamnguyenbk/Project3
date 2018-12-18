@extends("admin.layout.index")

@section("content")
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="fa fa fa-bars"></i>Website hỗ trợ quản lí đồ án </h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="{{url()->current()}}">Trang chủ/Cập nhật thông tin admin</a></li>
                </ol>
                <h4 class="page-header"><i class="fa fa fa-bars"></i>Admin: 
                    <small>{{$admin->username}}</small>
                </h4>
            </div>
        </div>  
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    <div class="form">
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

                        <form class="form-validate form-horizontal" method="POST" action="admin/user/updateAdmin/{{$admin->username}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" />
                            <div class="form-group ">
                                <label class="control-label col-lg-2">Username<font color="red"> *</font></label>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" name="username" value="{{$admin->username}}" />
                                </div>
                                <label class="control-label col-lg-2">Họ tên<font color="red"> *</font></label>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" name="full_name" value="{{$admin->full_name}}" />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label col-lg-2">Email<font color="red"> *</font></label>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" name="email" value="{{$admin->email}}" />
                                </div>
                                <label class="control-label col-lg-2">Số điện thoại</label>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" name="phone" value="{{$admin->phone}}" />
                                </div>
                            </div>
                            <div class="control-label col-lg-6">
                                <div>
                                    <button class="btn btn-default" type="submit">Sửa</button>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
</section>
@endsection