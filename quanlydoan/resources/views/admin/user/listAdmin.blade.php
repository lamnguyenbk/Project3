@extends("admin.layout.index")

@section("content")
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="fa fa fa-bars"></i>Website hỗ trợ quản lí đồ án </h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="{{url()->current()}}">Trang chủ/Danh sách admin</a></li>
                </ol>
            </div>  
        </div>
        <div class="row">
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
                            <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="admin/user/addAdmin">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                <div class="form-group ">
                                    <label class="control-label col-lg-2">Username<font color="red"> *</font></label>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="username" />
                                    </div>
                                    <label class="control-label col-lg-2">Password<font color="red"> *</font></label>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="password" />
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-2">Họ tên<font color="red"> *</font></label>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="full_name" />
                                    </div>
                                    <label class="control-label col-lg-2">Email<font color="red"> *</font></label>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="email" />
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-2">Số điện thoại</label>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="phone" />
                                    </div>
                                </div>
                                <div class="control-label col-lg-6">
                                    <div>
                                        <button class="btn btn-default" type="submit" name="action" value="add">Thêm</button>
                                    </div>
                                </div>
                            </form>    
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form">
                    <form class="form-inline">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                        <i class="glyphicon glyphicon-search"></i><label>Tìm Kiếm</label>
                        <div class="form-group mx-sm-3 mb-2">
                            <input type="" class="form-control" id="search" name="search">
                        </div>
                    </form>
                </div>

                <section class="panel">
                    <header class="panel-heading">
                        Danh sách người dùng Admin
                    </header>
                    <div class="result">
                        
                    </div>
                    
                    <script type="text/javascript">
                        fetch_customer_data('');

                        function fetch_customer_data(query){
                                var url = "admin/user/listAdmin/search";
                                var data = {};
                                data['search'] = query;
                                var get = $(this).attr('method');
                                $.ajax({
                                    type : get,
                                    url : url,
                                    data: data
                                }).done(function(data){
                                    $('.result').html(data);
                                })
                        }

                        $(document).on('keyup','#search',function(){
                               var query = $(this).val();
                               fetch_customer_data(query);
                           });

                        $(document).on('click', '.pagination a', function(e){
                            e.preventDefault();
                            var page=$(this).attr('href').split('page=')[1];
                            getAdmin(page, $('#search').val());
                        })

                        function getAdmin(page, search){
                            $.ajax({
                                type : 'get',
                                url : 'admin/user/listAdmin/searchPagination' +'?page='+page,
                                data : {'search': search} 
                            }).done(function(data){
                                $('.result').html(data);
                            })
                        }

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