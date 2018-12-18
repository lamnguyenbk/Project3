<table class="table" id="table_project">
    <thead id="thead">
        <tr>
          <th scope="col" class="col-sm-2">Giảng viên</th>
          <th scope="col" class="col-sm-3">Tên nhóm</th>
          <th scope="col" class="col-sm-4">Đề tài</th>
          <th scope="col" class="col-sm-2">Đã hoàn thành</th>
          <th scope="col" class="col-sm-1">Thao tác</th>
        </tr>
    </thead>
    <tbody>
    @foreach($listProject as $row) 
        <tr>
            <td>{{$row->full_name}}</td>
            <td>{{$row->group_name}}</td>
            <td>{{$row->project_name}}</td>
            @if($row->finish_project == 1)
              <td>Đã hoàn thành</td>
            @else
              <td>Chưa hoàn thành</td>
            @endif
            <td><a href="{{url()->current()}}/{{$row->id_group}}" class="btn btn-primary text-center">Chi tiết</a></td>
        </tr>
    @endforeach
    </tbody>
    
</table>
