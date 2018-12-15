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
                            <a class="btn btn-primary" href="teacher/project/{{$project->id_group}}/scheduel">Quản lí lịch trình
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="teacher/project/{{$project->id_group}}/evaluation">Đánh giá nhóm</a>
                        </div>
                        <div class="col-sm-3">
                            <a class="list-group-item-success btn btn-default" href="teacher/project/{{$project->id_group}}/listStudent">Danh sách
                                sinh viên</a>
                        </div>
                    </header>
                    <div class="panel-body">
                        <!--  lich trình thực hiện đồ án -->
                        <div class="tab-pane">
                            <section class="panel">
                                <div class="panel-body bio-graph-info">
                                    <div id="update_scheduel" class="row panel-body">
                                        <div>
                                            <h3><i class="glyphicon glyphicon-forward" style="padding:10px 0;"></i> Chỉnh sửa nội dung kế hoạch</h3>
                                        </div>
                                        @if(count($errors) > 0)
                                        <div class="alert alert-danger">
                                            @foreach($errors->all() as $err)
                                            {{$err}}<br>
                                            @endforeach
                                        </div>
                                        @endif

                                        <div class="form">
                                            <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="{{url()->current()}}">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10"></div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Mã tài liệu</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name="require">{{$content->require}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Mô tả yêu cầu</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name="descript">{{$content->descript}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Thời
                                                        hạn</label>
                                                    <div class="col-sm-3">
                                                        <input class="form-control" type="date" value="{{$content->time_deadline}}" name="time_deadline"/>
                                                    </div>
                                                    <label class="control-label col-sm-4">Trừ</label>
                                                    <div class="col-sm-1">
                                                        <input class="form-control" value="{{$content->penalty}}" name="penalty" type="number" step="0.25" min="0" max="10"/>
                                                    </div>
                                                </div>
                                                <div class="control-label col-sm-6
                                                                              text-center">
                                                    <button class="btn btn-default" type="submit">Cập nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div>
                                        <h3><i class="glyphicon glyphicon-forward" style="padding:10px 0;"></i> Kế
                                            hoạch thực hiện
                                            đồ án do bộ môn đưa ra</h3>
                                    </div>
                                    <div class="row">
                                        <table class="table">
                                            <tr>
                                                <td class="col-sm-3"><b>Nhiệm vụ</b></td>
                                                <td class="col-sm-7"><b>Mô tả yêu cầu</b></td>
                                                <td class="col-sm-1"><b>Deadline</b></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>1. Đề xuất đề tài</p>
                                                </td>
                                                <td>
                                                    <p>Các nhóm xác định đề tài và thông qua sự
                                                        xác nhận của giáo viên hướng dẫn, tài liệu
                                                        yêu cầu:
                                                        báo cáo đề xuất đề tài
                                                    </p>
                                                </td>
                                                <td>
                                                    <p>21/09/2018</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>2. Phân tích yêu cầu phần mềm</p>
                                                </td>
                                                <td>
                                                    <p>Các nhóm tiến hành phân tích yêu cầu phần
                                                        mềm, hoàn thành tài liệu phân tích yêu cầu
                                                        phần mềm</p>
                                                </td>
                                                <td>
                                                    <p>20/10/2018</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>3. Thiết kê hệ thống</p>
                                                </td>
                                                <td>
                                                    <p>Các nhóm tiến hành thiết kế hệ thống phần
                                                        mềm, hoàn thành tài liệu thiết kế phần mềm</p>
                                                </td>
                                                <td>
                                                    <p>20/10/2018</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>4. Kiểm thử hệ thống</p>
                                                </td>
                                                <td>
                                                    <p>Các nhóm tiến hành kiểm thử hệ thống phần
                                                        mềm, hoàn thành tài liệu kiểm thử phần mềm</p>
                                                </td>
                                                <td>
                                                    <p>20/11/2018</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
            </section>
        </div>
    </div>
</section>
@endsection