@extends("admin.layout.index")

@section("content")
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="fa fa fa-bars"></i>Website hỗ trợ quản lí đồ án </h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="{{url()->current()}}">Trang chủ/Lịch trình đồ án/IT4421</a></li>
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

                                    <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="admin/scheduel/addScheduel/1">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                        <div class="form-group ">
                                            <label class="control-label col-sm-3">Kì</label>
                                            <div class="col-lg-3">
                                                <select class="form-control" name="semester" id="semester">
                                                    <option value="20182">20182</option>
                                                    <option value="20181" selected>20181</option>
                                                    <option value="20172">20172</option>
                                                    <option value="20171">20171</option>
                                                    <option value="20162">20162</option>
                                                    <option value="20161">20161</option>
                                                </select>
                                            </div>
                                        </div>   
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Mã Tài Liệu</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="require"></textarea> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Mô tả yêu cầu</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="descript"></textarea> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Deadline</label>
                                            <div class="col-sm-3">
                                               <input class="form-control" type="date" name="time_deadline" />
                                           </div>
                                           <label  class="control-label col-sm-1">Trừ</label>
                                           <div class="col-sm-1">
                                            <input class="form-control" name="penalty" type="number" step="0.25" min="0" max="10" value="0"/>
                                        </div>
                                    </div>
                                    <div class="control-label col-sm-6 text-center">
                                        <button class="btn btn-default" type="submit" name="action" value="add">Thêm</button>
                                    </div>
                                </form>
                            </div>
                        </div> 
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <a href="{{url()->current()}}">Danh sách các nội dung thực hiện</a>
                </header>
                <div class="result">

                </div>

                <script type="text/javascript">


                    var yourSelect = document.getElementById( "semester" );
                    var semester =  yourSelect.options[ yourSelect.selectedIndex ].value;
                    fetch_customer_data(semester);

                    function fetch_customer_data(semester){
                        var url = "{{url()->current()}}" + "/search";
                        var data = {};
                        data['semester'] = semester;
                        var get = $(this).attr('method');
                        $.ajax({
                            type : get,
                            url : url,
                            data: data
                        }).done(function(data){
                            console.log(data);
                            $('.result').html(data);
                        })
                    }
                    $('#semester').change(function () {
                      var semester = $('#semester').find("option:selected").val();
                      fetch_customer_data(semester);
                  });

              </script>
              <script>
                $.ajaxSetup({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
    </section>
</div>
</div>
</section>
</section>
@endsection