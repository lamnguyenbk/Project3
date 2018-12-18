@extends("admin.layout.index")

@section("content")
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="fa fa fa-bars"></i>Website hỗ trợ quản lí đồ án </h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="">Trang chủ/Cập nhật lịch trình đồ án/IT4421</a></li>
                </ol>
            </div>
        </div>  
        <div class="row">
            <div class="col-lg-12">
                <div id="create_scheduel" class="tab-pane">
                    <section class="panel">
                        <div class="panel-body bio-graph-info">
                            <div class="row">
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

                                    <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="admin/scheduel/updateScheduel/1/{{$content_sub_scheduel->id_subject_scheduel}}/{{$content_sub_scheduel->id_content_scheduel}}">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Mã Tài Liệu</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="require">{{$content_sub_scheduel->require}}</textarea> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Mô tả yêu cầu</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="descript">{{$content_sub_scheduel->descript}}</textarea> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Deadline</label>
                                            <div class="col-sm-3">
                                               <input class="form-control" type="date" name="time_deadline" value="{{$content_sub_scheduel->time_deadline}}" />
                                           </div>
                                           <label  class="control-label col-sm-1">Trừ</label>
                                           <div class="col-sm-2">
                                            <input class="form-control" name="penalty" value="{{$content_sub_scheduel->penalty}}" type="number" step="0.25" min="0" max="10" />
                                        </div>
                                    </div>
                                    <div class="control-label col-sm-6 text-center">
                                        <button class="btn btn-default" type="submit">Cập Nhật</button>
                                    </div>
                                </form>
                            </div>
                        </div> 
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
</section>
@endsection