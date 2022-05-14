@extends('admin.layouts._main')

@section('title', 'POS | Businesses List')

@section('main-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Business List</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <form action="{{route('search-businesses')}}" method="GET">
                                    <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="query" placeholder="Search here....." value="{{ request()->input('query') }}">
                                        <span class="text-danger">@error('query'){{ $message }} @enderror</span>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Owner</th>
                                        <th>Archive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($businesses as $business)
                                    <tr>
                                        <td>{{$business->name}}</td>
                                        <td>{{$business->address}}</td>
                                        <td>{{$business->phone}}</td>
                                        <td>{{$business->email}}</td>
                                        <td>{{$business->website}}</td>
                                        <td>{{$business->user->name}}</td>
                                        <td>
                                            @if($business->status === 'pending')
                                                <form action="{{route('archive-business',['id' => $business->id])}}" method="POST" id = "business_{{$business->id}}">
                                                    @csrf
                                                    <input type="checkbox" class="btn btn-warning btn-sm" onclick="archive('business_{{$business->id}}')" >
                                                </form>

                                            @elseif($business->status === 'archived')
                                                <form action="{{route('unarchive-business',['id' => $business->id])}}" method="POST" id = "business_{{$business->id}}">
                                                    @csrf
                                                    <input type="checkbox" class="btn btn-warning btn-sm" onclick="unarchive('business_{{$business->id}}')" checked>
                                                </form>
                                            @endif

                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{route('edit-business',['id' => $business->id])}}">Edit</a>

                                            <button type="submit" class="btn btn-danger btn-sm" data-businessid="{{ $business->id }}"
                                                    data-toggle="modal" data-target="#deleteModal">Delete</button>

                                        </td>
                                    </tr>

                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Request</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>

                                                <form action="{{route('delete-business')}}" method="get">

                                                    <div class="modal-body">
                                                        <p>Select "Delete" below if you are ready to delete the current business.</p>
                                                        <input type="hidden" name="businesses_id" id="business_id" value="">
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                  <h1>Buinesses</h1>  
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="businesses" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Website</th>
                                            <th>Owner</th>
                                            <th>Archive</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>

        <div>
            {{$businesses->links('paginationLinks')}}
        </div>
        <!-- /.content -->

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure, you want to delete?</p>
                        <form id="deleteForm" action="" method="post" asp-antiforgery="true">
                            @csrf
                            <input type="hidden" id="deleteId" value="" name="id" />
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="deleteButton" class="btn btn-danger">Yes, Delete!</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.content-wrapper -->

@endsection

@section('styles')
    <link rel="stylesheet" href="/adminTheme/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
@endsection

@section('scripts')
    <script src="/adminTheme/plugins/datatables/jquery.dataTables.js"></script>
    <script src="/adminTheme/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script>
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var business_id = button.data('businessid')
            var modal = $(this)
            modal.find('.modal-body #business_id').val(business_id);
        })

        function archive(formToSubmit){
            document.getElementById(formToSubmit).submit();
        }

        function unarchive(formToSubmit){
            document.getElementById(formToSubmit).submit();
        }

        $(function () {
            $('#businesses').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/admin/getBusinessData",
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": 6,
                        "render": function (data, type, row) {
                            if(data == "true")
                                return `<input type="checkbox" class="btn btn-warning btn-sm"  checked>`;
                            else
                                return `<input type="checkbox" class="btn btn-warning btn-sm">`;
                        }
                    },
                    {
                        "orderable": false,
                        "targets": 7,
                        "render": function (data, type, row) {
                            return `<button type="submit" class="btn btn-info btn-sm" onclick="window.location.href='/admin/course/edit/${data}'" value='${data}'>
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </button>
                                    <button type="submit" class="btn btn-danger btn-sm show-bs-modal" href="#" data-id='${data}' value='${data}'>
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </button>`;
                        }
                    }
                ]
            });

            $('#businesses').on('click', '.show-bs-modal', function (event) {
                var id = $(this).data("id");
                var modal = $("#modal-default");
                modal.find('.modal-body p').text('Are you sure you want to delete this record?')
                $("#deleteId").val(id);
                $("#deleteForm").attr("action", "{{route('delete-business')}}")
                modal.modal('show');
            });

            $("#deleteButton").click(function () {
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
