@extends("admin.layout.index")

@section("content")
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="fa fa fa-bars"></i>Website hỗ trợ quản lí đồ án </h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="{{url()->current()}}">Trang chủ/Danh sách nhóm đồ án</a></li>
                </ol>
            </div>
        </div>  
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form >
                                <div class="form-group ">
                                    <label class="control-label col-lg-2">Kì</label>
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

                            </form>
                            <form class="form-inline">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                <i class="glyphicon glyphicon-search"></i><label>Giảng Viên</label>
                                <div class="form-group mx-sm-3 mb-2">
                                    <input type="" class="form-control" id="search" name="search">
                                </div>
                            </form>
                        </div>
                    </div>  
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <a href="{{url()->current()}}">Danh sách các nhóm đồ án</a>
                    </header>

                    <div class="result">

                    </div>

                    <script type="text/javascript">

                        
                        fetch_customer_data('', '20181');

                        function fetch_customer_data(query, semester){
                            var url = "{{url()->current()}}" + "/search";
                            var data = {};
                            data['search'] = query;
                            data['semester'] = semester;
                            var get = $(this).attr('method');
                            $.ajax({
                                type : get,
                                url : url,
                                data: data
                            }).done(function(teacher, student, doc){
                                $('.result').html(teacher, student, doc);
                            })
                        }

                        $(document).on('keyup','#search',function(){
                        var query = $('#search').val();
                        var semester = $('#semester').find("option:selected").val();
                        fetch_customer_data(query, semester);
                    });
                        $('#semester').change(function () {
                        var query = $('#search').val();
                        var semester = $('#semester').find("option:selected").val();
                        fetch_customer_data(query, semester);
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