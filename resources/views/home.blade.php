<!DOCTYPE html>
<html lang="en">

<head>
    <title>Akhand Crud</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('plugin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link href="{{ asset('plugin/css/toastr.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('plugin/fontawesome-free/css/all.min.css')}}"> 
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <script src="{{ asset('plugin/js/bootstrap.bundle.min.js') }}"></script>
</head>

<body>
    <div class="container mt-3">
        <div class="loader" style="display: none"></div>
        <div class="card">
            <div class="card-header">
                <h3 class="text-center mb-0"> <span class="text-center heading">Tutorials Record</span>
                    <button onclick="exportToExcel()" style="float: right" class="btn btn-warning mx-1"> <i
                            class="fas fa-file-excel"></i> Export To Excel</button>
                    <button type="button" class="btn btn-primary" style="float: right" data-bs-toggle="modal"
                        data-bs-target="#myModal"><i class='fas fa-file-import'></i>
                        Import CSV
                    </button>
                    <button class="btn btn-info mx-1 button" style="float: right" onclick="ShowAddModal()"><i
                            class="fa fa-plus"></i> Add Data</button>
                </h3>
            </div>
            {{-- Table data append in this div by jquery ajax --}}
            <div class="card-body p-0 " id="Jtbldata">
            </div>
        </div>
    </div>
    {{-- Import Modal --}}
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Import</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="container">
                        <form action="" method="post" enctype="multipart/form-data" id="upload_form">
                            @csrf
                            <div class="form-group mb-1">
                                <label style="font-weight: bold" for="file">Please Select File <sup
                                        class="require">*</sup></label>
                                <input type="file" name="file" class="form-control" id="exampleFormControlFile1">
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Add & Edit Modal handel by jquery --}}
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title insert"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="container">
                        <form action="" method="post" enctype="multipart/form-data" id="update_form">
                            @csrf
                            <div class="form-group mb-1">
                                <label style="font-weight: bold" for="file"> Title <sup
                                        class="require">*</sup></label>
                                <input type="hidden" name="tutorial_id" class="form-control" id="tid">
                                <input type="text" name="tutorial_title" class="form-control" id="title">
                                <span class="require tutorial_title"></span>
                            </div>
                            <div class="form-group mb-1">
                                <label style="font-weight: bold" for="file">Auther <sup
                                        class="require">*</sup></label>
                                <input type="text" name="tutorial_author" class="form-control" id="auther">
                                <span class="require tutorial_author"></span>
                            </div>
                            <div class="form-group mb-1">
                                <label style="font-weight: bold" for="file">Date <sup
                                        class="require">*</sup></label>
                                <input type="date" name="submission_date" class="form-control" id="submit_date">
                                <span class="require submit_date"></span>
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" name="submit" id="updateBtn" value="Update"
                                    style="display: none" class="btn btn-primary">
                                <input type="submit" name="submit" id="submitBtn" value="Submit"
                                    class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-responsive text-center  mb-3" id="tData1" style="display: none">
        <thead>
            <th style="border: 1px solid black;">Tutorial Id</th>
            <th style="border: 1px solid black">Title</th>
            <th style="border: 1px solid black">Author</th>
            <th style="border: 1px solid black">Date</th>
        </thead>
        <tbody id="tblData" style="border:  1px solid black">
            @foreach ($datas as $data)
                <tr style="border:  1px solid black">
                    <td style="border:  1px solid black">{{ $data->tutorial_id }}</td>
                    <td style="border:  1px solid black">{{ $data->tutorial_title }}</td>
                    <td style="border:  1px solid black">{{ $data->tutorial_author }}</td>
                    <td style="border:  1px solid black">{{ $data->submission_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script src="{{ asset('plugin/js/jquery.min.js') }}"></script>
    <script src="{{asset('plugin/Excel/table2excel.js')}}"></script>
    <script src="{{ asset('plugin/js/toastr.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Fetch table data on page load
            GetData();
        });
        // Export table data in to excel 
        function exportToExcel() {
            $("#tData1").table2excel({
                filename: "Tutorials"
            });
        }
        // Open  add modal 
        function ShowAddModal() {
            $('#update_form')[0].reset();
            $("#editModal").modal("show");
            $(".insert").text("Add Data");
            $("#updateBtn").hide();
            $("#submitBtn").show();
        }
        // Add form data using modal
        $("#submitBtn").click(function(e) {
            e.preventDefault(); //form will not submitted  
            if (validation()) {
                $.ajax({
                    type: "post",
                    url: "{{ url('/save') }}",
                    data: {
                        'tutorial_title': $("#title").val().trim(),
                        'tutorial_author': $("#auther").val().trim(),
                        'submission_date': $("#submit_date").val(),
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        var data = $.parseJSON(res);
                        if (data.status == 200) {
                            toastr.success(data.message);
                            $('#update_form')[0].reset();
                            $("#editModal").modal("hide");
                            GetData()
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function(res) {
                        toastr.error('Something went Wrong.');
                    }
                });
            }
        })
        // pagination link handel by jquery
        $(document).on('click', '.page-item a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            GetData(page);
        });
        // Fetch table data
        function GetData(page) {
            $.ajax({
                url: "{{ url('/get-data') }}?page=" + page,
                method: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    // loader show
                    $(".loader").show();
                    $(".card").addClass("blur");
                },
                success: function(res) {
                    $(".loader").hide();
                    $(".card").removeClass("blur");
                    $("#Jtbldata").html(res);
                }
            })
        }
        // Submit form using Ajax 
        $('#upload_form').on("submit", function(e) {
            e.preventDefault(); //form will not submitted  
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/upload') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false, // The content type used when sending data to the server.  
                cache: false, // To unable request pages to be cached  
                processData: false, // To send DOMDocument or non processed data file it is set to false  
                success: function(res) {
                    var data = $.parseJSON(res);
                    if (data.status == 200) {
                        toastr.success(data.message);
                        $('#upload_form')[0].reset();
                        $("#myModal").modal("hide");
                        GetData()
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(res) {
                    toastr.error('Something went Wrong.');
                }
            });
        });
        // Edit Data Fetch from html table 
        function showEditModal(id) {
            $("#editModal").modal("show");
            $(".tutorial_title").text("");
            $(".tutorial_author").text("");
            $(".submit_date").text("");
            $(".insert").text("Update Record");
            $("#updateBtn").show();
            $("#submitBtn").hide();
            $("#tData tr").each(function() {
                var tid = $(this).find('.tid').text();
                if (Number(tid) == id) {
                    $("#tid").val($(this).find('.tid').text());
                    $("#title").val($(this).find('.title').text());
                    $("#auther").val($(this).find('.auther').text());
                    $("#submit_date").val($(this).find('.sdate').attr('date'));
                }
            })
        }
        // Delete Record from table
        function DeleteData(id) {
            // Confirmation check before delete
            if (!confirm('Are you sure?')) return false;
            $.ajax({
                url: "{{ url('/delete') }}",
                method: "POST",
                data: {
                    "tutorial_id": id,
                    "_token": '{{ csrf_token() }}'
                },
                success: function(res) {
                    var data = $.parseJSON(res);
                    if (data.status == 200) {
                        toastr.success(data.message);
                        GetData();
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(res) {
                    toastr.error('Something went Wrong.');
                }
            })
        }
        // validation when update & add data
        function validation() {
            if (!$("#title").val()) {
                $(".tutorial_title").text("Please enter title");
            } else {
                $(".tutorial_title").text("");
            }
            if (!$("#auther").val()) {
                $(".tutorial_author").text("Please enter auther");
            } else {
                $(".tutorial_author").text("");
            }
            if (!$("#submit_date").val()) {
                $(".submit_date").text("Please enter date");
            } else {
                $(".submit_date").text("");
            }
            if (!$("#title").val() || !$("#auther").val() || !$("#submit_date").val()) {
                return false;
            } else {
                return true;
            }
        }
        //Update record on modal data using Ajax
        $('#updateBtn').on("click", function(e) {
            e.preventDefault(); //form will not submitted  
            if (validation()) {
                $.ajax({
                    url: "{{ url('/update') }}",
                    method: "POST",
                    data: {
                        'tutorial_id': $("#tid").val(),
                        'tutorial_title': $("#title").val().trim(),
                        'tutorial_author': $("#auther").val().trim(),
                        'submission_date': $("#submit_date").val(),
                        "_token": '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        var data = $.parseJSON(res);
                        if (data.status == 200) {
                            toastr.success(data.message);
                            $('#update_form')[0].reset();
                            $("#editModal").modal("hide");
                            GetData()
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function(res) {
                        toastr.error('Something went Wrong.');
                    }
                })
            }
        });
    </script>
</body>
</html>
