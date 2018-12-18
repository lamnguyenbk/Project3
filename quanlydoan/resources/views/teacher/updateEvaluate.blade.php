@extends("layout.index")

@section("content")
<section class="container bg_container" style="margin-top: 80px;">
@foreach($projects as $project)
        <div id="main_text">
            <img src="img/register48x48.png" />&nbsp; {{$project->group_name}}
        </div>
        <div id="hr"></div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <div>
                    <div class="col-sm-3"><b>Học kỳ</b></div>
                    <div class="col-sm-9">
                        <p>{{$project->semester}}</p>
                    </div>
                </div>
                <form>
                    <div>
                        <div class="col-sm-3"><b>Tên đề tài</b></div>
                        <div class="col-sm-8">
                            <p>{{$project->project_name}}</p>
                        </div>
                    </div>
                </form>
                <div>
                    <div class="col-sm-3"><b>Giảng viên hướng dẫn</b></div>
                    <div class="col-sm-9">
                        <p>{{$project->full_name}}</p>
                    </div>
                </div>
                <div id="hr1"></div>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading text-center">
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="teacher/project/{{$project->id_group}}/document">Tài liệu đồ án</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="teacher/project/{{$project->id_group}}/scheduel">Quản lí lịch trình</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-primary" href="teacher/project/{{$project->id_group}}/evaluation">Đánh giá nhóm</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="teacher/project/{{$project->id_group}}/listStudent">Danh sách sinh viên</a>
                        </div>
                    </header>
                    <div class="panel-body">
                        <!-- Tieu chí đánh giá -->
                        <div class="tab-pane">
                            <section class="panel">
                                <div class="panel-body bio-graph-info">
                                    <div class="row panel-body" id="update_evaluate">
                                            <div>
                                                <h3><i class="glyphicon glyphicon-forward" style="padding:10px 0;"></i> Chỉnh sửa tiêu chí</h3>
                                            </div>
                                            @if(count($errors) > 0)
                                            <div class="alert alert-danger">
                                                @foreach($errors->all() as $err)
                                                {{$err}}<br>
                                                @endforeach
                                            </div>
                                            @endif
                                            <div class="panel-body tab-content tab-pane">
                                                <div class="form">
                                                    <form class="form-validate form-horizontal" id="feedback_form" method="POST"
                                                        action="{{url()->current()}}">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-1">Tiêu chí</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" name="content" value="{{$content->content}}"/>
                                                            </div>
                                                            <label class="control-label col-sm-4">Điểm cộng/trừ</label>
                                                            <div class="col-sm-1">
                                                                <input type="number" class="form-control" name="bonus" step="0.25" min="-5" max="10" value ="{{$content->bonus}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="control-label col-sm-6 text-center">
                                                            <button type="submit" class="btn btn-default">Cập nhật</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                    </div>
                                    
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection