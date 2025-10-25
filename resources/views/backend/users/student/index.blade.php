@extends('backend.layouts.master')
@section('title', 'All Students')
@section('main-content')

<div class="container mt-4">
    <h2>Student List</h2>
     <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.student.create') }}" class="btn btn-primary">Add Student</a>
    </div>


    <table class="table table-bordered table-striped" id="student-table">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </thead>
        {{-- <tbody>
            
        </tbody> --}}
    </table>

    {{-- Pagination Links --}}
    
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#student-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('admin/student/index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'image',     name: 'image', orderable: false, searchable: false},
                    {data: 'unique_id', name: 'unique_id'},
                    {data: 'name',      name: 'name'},
                    {data: 'email',     name: 'email'},
                    {data: 'phone',     name: 'phone'},
                    {data: 'gender',    name: 'gender'},
                    {data: 'action',    name: 'action', orderable: false, searchable: false},
                ]
            });
        });



        // Delete with SweetAlert
        $(document).on('click', '.deleteUser', function(){
            let userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'admin/student/delete/'+userId,
                        type: 'DELETE',
                        
                        success: function(response){
                            Swal.fire(
                                'Deleted!',
                                response.success,
                                'success'
                            )
                            $('#student-table').DataTable().ajax.reload(); // refresh table
                        },
                        error: function(xhr){
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            )
                        }
                    });
                }
            })
        });









    </script>
@endsection


