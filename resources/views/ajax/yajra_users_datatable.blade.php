@extends('backend.layouts.master')
@section('title', 'All Users')
@section('main-content')

<div class="container mt-4">
    <h2 class="mb-4 text-center">Users List</h2>
    <table class="table table-striped table-bordered table-hover" id="users-table">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('scripts')
   <script type="text/javascript">
        $(function () {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/admin/users') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {
                        data: 'role',
                        name: 'role',
                        render: function(data, type, row) {
                            let role = data.toUpperCase(); // uppercase

                            if (role === 'ADMIN') {
                                return `<span class="badge bg-danger">${role}</span>`;
                            } else if (role === 'TEACHER') {
                                return `<span class="badge bg-primary">${role}</span>`;
                            } else if (role === 'STUDENT') {
                                return `<span class="badge bg-success">${role}</span>`;
                            } else {
                                return `<span class="badge bg-secondary">${role}</span>`;
                            }
                        }
                    },
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
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
                        url: '/admin/users/'+userId,
                        type: 'DELETE',
                        
                        success: function(response){
                            Swal.fire(
                                'Deleted!',
                                response.success,
                                'success'
                            )
                            $('#users-table').DataTable().ajax.reload(); // refresh table
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
