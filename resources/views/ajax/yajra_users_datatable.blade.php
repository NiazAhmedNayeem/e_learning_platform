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
    </script>
@endsection
