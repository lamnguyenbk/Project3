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
                            <a class="list-group-item-success btn btn-default" href="student/project/{{$project->id_group}}/document">Tài liệu đồ án</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="student/project/{{$project->id_group}}/scheduel">Quản lí lịch trình</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-primary" href="student/project/{{$project->id_group}}/evaluation">Đánh giá nhóm</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="student/project/{{$project->id_group}}/listStudent">Danh sách
                                sinh viên</a>
                        </div>
                    </header>
                    <div class="panel-body">
                        <!-- Tieu chí đánh giá -->
                        <div class="tab-pane">
                            <section class="panel">
                                <div class="panel-body bio-graph-info">
                                    <div>
                                        <h3><i class="glyphicon glyphicon-forward" style="padding:10px 0;"></i> Tiêu chí đánh giá đồ án</h3>
                                    </div>
                                    @if(session('thongbao'))
                                    <div id="message_update" class="alert alert-success">
                                        {{session('thongbao')}}
                                    </div>
                                    @endif
                                    <!-- <div id="message_delete"></div> -->
                                    <div class="row">
                                        <table class="table" id="result">
                                            <tr>
                                                <td class="col-sm-10 text-center"><b>Tiêu chí</b></td>
                                                <td class="col-sm-2text-center"><b>Điểm cộng/trừ</b></td>
                                            </tr>
                                            @foreach($evaluations as $evaluation)   
                                            <tr>
                                                <td>
                                                    <p>{{$evaluation->content}}</p>
                                                </td>
                                                <td class="text-center">
                                                    <p>{{$evaluation->bonus}}</p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="panel-body tab-content tab-pane">
                                            <h3><i class="glyphicon glyphicon-forward" style="padding:10px 0;"></i> Đánh giá tổng thể nhóm</h3> 
                                            <h4 class="text-center">Điểm hiện tại: {{$point}}</h4>
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