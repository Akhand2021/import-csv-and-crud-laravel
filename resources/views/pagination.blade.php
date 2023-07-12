<table class="table table-responsive table-striped table-hover table-bordered text-center  mb-3"
id="tData">
<thead>
    <th>Tutorial Id</th>
    <th>Title</th>
    <th>Author</th>
    <th>Date</th>
    <th>Action</th>
</thead>
<tbody id="tblData">
@if(count($datas) === 0)
<tr>
    <td colspan="5">No Record Found</td>
</tr>
@else
    @foreach ($datas as $data)
        <tr>
            <td class="tid">{{ $data->tutorial_id }}</td>
            <td class="title">{{ $data->tutorial_title }}</td>
            <td class="auther">{{ $data->tutorial_author }}</td>
            <td class="sdate" date="{{$data->submission_date}}">{{ date("d-M-Y", strtotime($data->submission_date)) }}</td>
            <td><button class="btn btn-sm btn-success" onclick="showEditModal({{ $data->tutorial_id }})"><i class="fa fa-edit"> </i> Edit</button> <button
                                                                            class="btn btn-sm btn-danger" onclick="DeleteData({{ $data->tutorial_id }})"><i class="fa fa-trash"></i> Delete</button></td>
        </tr>
    @endforeach
    @endif
</tbody>
</table>
{!! $datas->links('vendor.pagination.bootstrap-5') !!}