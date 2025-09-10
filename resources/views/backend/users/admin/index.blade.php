@extends('backend.layouts.master')
@section('title', 'All admins User')
@section('main-content')

<div class="container mt-4">
    <h2>Admin User List</h2>


    <div class="d-flex justify-content-between mb-3">
        <!-- Search (left) -->
        <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search Admin...">
        </div>

        <!-- Add Button (right) -->
        <div>
            <button class="btn btn-primary" id="toggleAddForm">
                <i class="fas fa-plus"></i> Add New Admin
            </button>
            <button class="btn btn-danger" id="toggleCancelForm" style="display: none">
                <i class="fas fa-close"></i> Cancel
            </button>
        </div>
    </div>

    <div id="addAdminInput" class="mb-3 card card-body" style="display: none;">
        <div class="card card-body shadow mt-5">
            <h1 class="text-2xl font-bold text-center mt-4">Add New Admin</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form style="padding: 10px" id="addAdminForm" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @csrf

                <h3 class="text-lg font-semibold mb-3">Admin Information</h3>

                <div class="row card-body border rounded mb-4">

                    <div class="col-md-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" >
                        <div id="errorName" class="text-danger small"></div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" >
                        <div id="errorEmail" class="text-danger small"></div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control" >
                        <div id="errorPhone" class="text-danger small"></div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold">Password</label>
                        <input type="text" id="password" name="password" value="{{ old('password') }}" class="form-control" >
                        <div id="errorPassword" class="text-danger small"></div>
                    </div>

                    

                    <div class="col-md-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div id="errorStatus" class="text-danger small"></div>
                    </div>

                    

                    <div class="col-md-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold">Profile Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="col-md-12 mt-4 text-center">
                    <button id="cancelForm" class="btn btn-danger btn-lg shadow-sm">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        Create Admin
                    </button>
                </div>

            </form>
        </div>
    </div>



    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="admins">
            
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <nav>
        <ul class="pagination" id="paginationLinks"></ul>
    </nav>
    
</div>


    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="editAdminForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editName" name="name">
                                <small class="text-danger" id="editErrorName"></small>
                            </div>

                            <div class="col-md-6">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                                <small class="text-danger" id="editErrorEmail"></small>
                            </div>

                            <div class="col-md-6">
                                <label for="editPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="editPhone" name="phone">
                                <small class="text-danger" id="editErrorPhone"></small>
                            </div>

                            <div class="col-md-6">
                                <label for="editPassword" class="form-label">Password (Leave blank to keep unchanged)</label>
                                <input type="password" class="form-control" id="editPassword" name="password">
                                <small class="text-danger" id="editErrorPassword"></small>
                            </div>


                            <div class="col-md-6">
                                <label for="editStatus" class="form-label">Status</label>
                                <select class="form-control" id="editStatus" name="status" >
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <small class="text-danger" id="editErrorStatus"></small>
                            </div>

                            <div class="col-md-6">
                                <label for="editImage" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="editImage" name="image">
                            </div>

                            <div class="col-md-12 mt-2">
                                <img id="editPreviewImage" src="#" alt="Preview" class="img-thumbnail" style="max-height: 100px; display: none;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function(){
            //form open and close
            $('#toggleAddForm').click(function(){
                $('#addAdminInput').slideToggle();
                $('#toggleAddForm').hide();
                $('#toggleCancelForm').show();
            });

            $('#toggleCancelForm').click(function(){
                $('#addAdminInput').slideToggle();
                $('#toggleAddForm').show();
                $('#toggleCancelForm').hide();
                $('#addAdminForm')[0].reset();
                //error remove
                $('#addAdminForm').find('.text-danger').html('');
            });


            //form cancel
            $('#cancelForm').click(function(e){
                e.preventDefault();
                $('#addAdminInput').slideToggle();
                $('#toggleCancelForm').hide();
                $('#toggleAddForm').show();
                $('#addAdminForm')[0].reset();
                //error remove
                $('#addAdminForm').find('.text-danger').html('');
            });


            ///Global variable
            let currentPage = 1;
            let currentSearch = '';
            let authId = {{ auth()->id() }};

            ///data table
            function loadAdmins(page = 1, search = ''){
                currentPage = page;
                currentSearch = search;

                $.get("{{ url('/all/admin/data') }}?page=" + page + "&search=" + search, function(res){
                    let html = '';

                    if (res.data.length > 0) {
                        $.each(res.data, function(index, admin) {
                            html += `
                                <tr>
                                    <td>${res.from + index}</td>
                                    <td><img src="${admin.image_show}" width="50" height="50" class="rounded"></td>
                                    <td>${admin.name}</td>
                                    <td>${admin.email}</td>
                                    <td>${admin.phone}</td>
                                    <td>
                                        ${admin.is_super == 1 
                                            ? `<span class="badge bg-primary">Super ${admin.role.charAt(0).toUpperCase() + admin.role.slice(1)}</span>`
                                            : `<span class="badge bg-primary">${admin.role.charAt(0).toUpperCase() + admin.role.slice(1)}</span>`}
                                    </td>
                                    <td>
                                        <span class="badge ${admin.status == 1 ? 'bg-success' : 'bg-danger'}">
                                            ${admin.status == 1 ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editBtn" data-id="${admin.id}">Edit</button>
                                        ${authId != admin.id && admin.is_super != 1 
                                            ? `<button class="btn btn-danger btn-sm deleteBtn" data-id="${admin.id}">Delete</button>` 
                                            : ``}
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = `<tr><td colspan="8" class="text-center">No admins found</td></tr>`;
                    }

                    $("#admins").html(html);



                    // pagination links making
                    let links = '';
                    $.each(res.links, function(index, link){
                        let active = link.active ? 'active' : '';
                        links += `<li class="page-item ${active}">
                            <a class="page-link" href="#" data-page="${link.url ? link.url.split('page=')[1] : ''}">
                                ${link.label}
                            </a>
                        </li>`;
                    });
                    $("#paginationLinks").html(links);
                });
            }

            // first time load
            loadAdmins();


            //live search
            let typingTimer;
            $("#searchBox").on("keyup", function(){
                clearTimeout(typingTimer);
                let value = $(this).val();
                typingTimer = setTimeout(function(){
                    loadAdmins(1, value); // before search page = 1
                }, 300); // 300ms delay (user typing)
            });

            // Pagination new data load 
            $(document).on("click", "#paginationLinks a", function(e){
                e.preventDefault();
                let page = $(this).data("page");
                if(page) loadAdmins(page, currentSearch);
            });



            /// form submit
            $('#addAdminForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ url('/admin/store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res){
                        if(res.status === 'success'){
                            $('#addAdminForm')[0].reset();
                            loadAdmins();
                            $('#addAdminInput').slideToggle();
                            $('#toggleAddForm').show();
                            $('#toggleCancelForm').hide();
                            toastr.success(res.message, 'Success', {timeOut: 3000});
                        }
                    },
                    error: function(xhr){
                        if(xhr.status == 422){
                            let errors = xhr.responseJSON.errors;
                            $('#errorName').html(errors.name ? errors.name[0] : '');
                            $('#errorEmail').html(errors.email ? errors.email[0] : '');
                            $('#errorPhone').html(errors.phone ? errors.phone[0] : '');
                            $('#errorPassword').html(errors.password ? errors.password[0] : '');
                            $('#errorStatus').html(errors.status ? errors.status[0] : '');
                        }
                    }
                });
            });


            ///create form error remove
            $('#name').on('input', function(){
                $('#errorName').html('');
            });
            $('#email').on('input', function(){
                $('#errorEmail').html('');
            });
            $('#phone').on('input', function(){
                $('#errorPhone').html('');
            });
            $('#password').on('input', function(){
                $('#errorPassword').html('');
            });
            $('#status').on('input', function(){
                $('#errorStatus').html('');
            });

            ///edit form error remove
            $('#editName').on('input', function(){
                $('#editErrorName').html('');
            });
            $('#editEmail').on('input', function(){
                $('#editErrorEmail').html('');
            });
            $('#editPhone').on('input', function(){
                $('#editErrorPhone').html('');
            });
            $('#editPassword').on('input', function(){
                $('#editErrorPassword').html('');
            });
            // $('#editStatus').on('input', function(){
            //     $('#editErrorStatus').html('');
            // });




            // Bootstrap modal instance
            const editModal = new bootstrap.Modal(document.getElementById('editAdminModal'));

            // Open modal with data
            $(document).on('click', '.editBtn', function () {
                const id = $(this).data('id');

                $.get("{{ url('/admin/edit') }}/"+id, function (res) {
                    if (res.status === 'success') {
                        const admin = res.data;

                        $('#editId').val(admin.id);
                        $('#editName').val(admin.name);
                        $('#editEmail').val(admin.email);
                        $('#editPhone').val(admin.phone);
                        $('#editStatus').val(admin.status);
                        $('#editPassword').val(''); // leave blank
                        $('#editPreviewImage').attr('src', admin.image_show).show();

                        // Clear errors
                        $('#editAdminForm').find('.text-danger').html('');

                        let authId = {{ auth()->user()->id }};
                        if (authId === admin.id) {
                            $('#editStatus').prop('disabled', true);
                        } else {
                            $('#editStatus').prop('disabled', false);
                        }

                        editModal.show();
                    }
                });
            });

            // Update form submit
            $('#editAdminForm').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const id = $('#editId').val();

                $.ajax({
                    url: "{{ url('/admin/update') }}/"+id,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status === 'success') {
                            editModal.hide();
                            loadAdmins(currentPage, currentSearch);
                            toastr.success(res.message, 'Success', { timeOut: 3000 });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('#editErrorName').text(errors.name ? errors.name[0] : '');
                            $('#editErrorEmail').text(errors.email ? errors.email[0] : '');
                            $('#editErrorPhone').text(errors.phone ? errors.phone[0] : '');
                            $('#editErrorPassword').text(errors.password ? errors.password[0] : '');
                            // $('#editErrorStatus').text(errors.status ? errors.status[0] : '');
                        }
                    }
                });
            });


            //Delete category
            $(document).on('click', '.deleteBtn', function(){
                let id = $(this).data('id');
                let tr = $(this).closest('tr');

                
                $('#deleteRow').remove();

                // Confirm row HTML
                let deleteConfirm = `
                    <tr id="deleteRow">
                        <td colspan="6" class="text-center">
                            Are you sure you want to delete this category? 
                            <button class="btn btn-sm btn-danger confirmDelete" data-id="${id}">Yes</button>
                            <button class="btn btn-sm btn-secondary cancelDelete">No</button>
                        </td>
                    </tr>
                `;

                tr.after(deleteConfirm);
            });

            $(document).on('click', '.cancelDelete', function(){
                $('#deleteRow').remove();
            });

            $(document).on('click', '.confirmDelete', function(){
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('admin/delete') }}/" + id,
                    method: "DELETE",
                    success: function(res){
                        if(res.status === 'success'){
                            $('#deleteRow').remove(); // confirm row remove
                            loadAdmins(currentPage, currentSearch); // table refresh
                            toastr.success(res.message, 'Success', {timeOut: 3000});
                        }
                    }
                });
            });



        }); //document ready function end
    </script>
@endsection



