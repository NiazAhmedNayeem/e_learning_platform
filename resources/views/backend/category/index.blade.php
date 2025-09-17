@extends('backend.layouts.master')
@section('title', 'All Categories')
@section('main-content')

<div class="container mt-4">
    <h2 class="mb-4">Categories List</h2>
    

    <div class="d-flex justify-content-between mb-3">
        <!-- Search (left) -->
        <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search category...">
        </div>

        <!-- Add Button (right) -->
        <div>
            <button class="btn btn-primary" id="toggleAddForm">
                <i class="fas fa-plus"></i> Add New Category
            </button>
        </div>
    </div>


    <!-- Add Category Form (hidden by default) -->
    <div id="addCatInput" class="mb-3 card card-body" style="display: none;">
        <form id="addCategory" class="d-flex align-items-center gap-2 flex-wrap">
            @csrf
            
                <!-- Name -->
                <div class="flex-grow-1">
                    <label for="catName" class="form-label">Category Name</label>
                    <input type="text" id="catName" name="name" class="form-control" placeholder="Enter category name">
                    <div id="errorName" class="text-danger small"></div>
                </div>

                <!-- Image -->
                <div class="flex-grow-1">
                    <label for="catImage" class="form-label">Category Image</label>
                    <input type="file" id="catImage" name="image" class="form-control">
                    <div id="errorImage" class="text-danger small"></div>
                </div>

                <!-- Status -->
                <div>
                    <label for="catStatus" class="form-label">Status</label>
                    <select name="status" id="catStatus" class="form-control">
                        <option value="1" >Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div id="errorStatus" class="text-danger small"></div>
                </div>

                <!-- Submit Button -->
                <div class="align-self-end">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>

        </form>
    </div>


    <!-- Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody id="categories">
            
        </tbody>
    </table>

    <nav>
        <ul class="pagination" id="paginationLinks"></ul>
    </nav>


</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        
        //show form 
        $('#toggleAddForm').click(function(){
            $('#addCatInput').slideToggle();
        });

        ///Global variable
        let currentPage = 1;
        let currentSearch = '';

        function loadCategories(page = 1, search = ''){
            currentPage = page;
            currentSearch = search;

            $.get("{{ url('/categories') }}?page=" + page + "&search=" + search, function(res){
            
                let html = '';

                if(res.data && res.data.length > 0){
                    $.each(res.data, function(index, cat){
                        html += `
                            <tr>
                                <td>${res.from + index}</td>
                                <td><img src="${cat.image_show}" width="50" height="50" class="rounded"></td>
                                <td>${cat.name}</td>
                                <td>${cat.slug}</td>
                                <td>
                                    <span class="badge ${cat.status == 1 ? 'bg-success' : 'bg-danger'}">
                                        ${cat.status == 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-id="${cat.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteBtn" data-id="${cat.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = `<tr><td colspan="6" class="text-center">No categories found</td></tr>`;
                }

                $("#categories").html(html);

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
        loadCategories();


        //live search
        let typingTimer;
        $("#searchBox").on("keyup", function(){
            clearTimeout(typingTimer);
            let value = $(this).val();
            typingTimer = setTimeout(function(){
                loadCategories(1, value); // before search page = 1
            }, 300); // 300ms delay (user typing)
        });

        // Pagination new data load 
        $(document).on("click", "#paginationLinks a", function(e){
            e.preventDefault();
            let page = $(this).data("page");
            if(page) loadCategories(page, currentSearch);
        });

          

        ///error remove
        $('#catName').on('input', function(){
            $('#errorName').html('');
        });
        $('#catStatus').on('input', function(){
            $('#errorStatus').html('');
        });

        

        //form submit
        $('#addCategory').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            // let name = $('#catName').val();
            //let image = $('#catImage').val();
            // let status = $('#catStatus').val();

            $.ajax({
                url: "{{ url('/category-store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                // data: {name:name, status:status},
                success: function(res){
                    if(res.status === 'success'){
                        $('#catName').val('');
                        $('#catImage').val('');
                        // $('#catStatus').val('');
                        loadCategories();
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                },
                error: function(xhr){
                    if(xhr.status == 422){
                        let errors = xhr.responseJSON.errors;
                        if(errors.name){
                            $('#errorName').html('<p style="color:red;">'+errors.name[0]+'</p>');
                        }else{
                            $('#errorName').html('');
                        }
                        if(errors.status){
                            $('#errorStatus').html('<p style="color:red;">'+errors.status[0]+'</p>');
                        }else{
                            $('#errorStatus').html('');
                        }
                        
                    }
                },
            });
        });


        //Edit form
        $(document).on('click', '.editBtn', function(){
            let id = $(this).data('id');
            let tr = $(this).closest('tr'); 

            
            $('#editRow').remove();
            $('#deleteRow').remove();

            
            $(this).prop('disabled', true);

            $.get("{{ url('/categories') }}/" + id, function(res){
                let editForm = `
                    <tr id="editRow">
                        <td colspan="6">
                            <form id="editCategoryForm" class="row g-3 align-items-end" enctype="multipart/form-data">
                                <input type="hidden" id="editCatId" name="id" value="${res.id}">

                                <div class="col-md-3">
                                    <label class="form-label">Category Image</label>
                                    <input type="file" id="editCatImage" name="image" class="form-control">
                                    <div id="editErrorImage" class="text-danger small"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" id="editCatName" name="name" class="form-control" value="${res.name}">
                                    <div id="editErrorName" class="text-danger small"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select id="editCatStatus" name="status" class="form-select">
                                        <option value="1" ${res.status == 1 ? 'selected' : ''}>Active</option>
                                        <option value="0" ${res.status == 0 ? 'selected' : ''}>Inactive</option>
                                    </select>
                                    <div id="editErrorStatus" class="text-danger small"></div>
                                </div>

                                <div class="col-md-3">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="submit" id="cancelEditForm" class="btn btn-danger">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                `;
                tr.after(editForm);
            }).always(function(){
                
                $('.editBtn').prop('disabled', false);
            });
        });

        ///edit form cancel
        $(document).on('click', '#cancelEditForm', function(e){
            e.preventDefault();
            $('#editRow').remove();
        });


        ///update form
        $(document).on('submit', '#editCategoryForm', function(e){
            e.preventDefault();
            let id = $('#editCatId').val();
            
            let formData = new FormData(this);

            // let name = $('#editCatName').val();
            // let status = $('#editCatStatus').val();

            $.ajax({
                url: "{{ url('/category-update') }}/" + id,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                // data: {
                //     name: name,
                //     status: status
                // },
                success: function(res){
                    if(res.status === 'success'){
                        $('#editRow').remove(); // edit row remove
                        loadCategories(currentPage, currentSearch);       // table reload
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                },
                error: function(xhr){
                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        $('#editErrorName').html(errors.name ? errors.name[0] : '');
                        $('#editErrorImage').html(errors.image ? errors.image[0] : '');
                        $('#editErrorStatus').html(errors.status ? errors.status[0] : '');
                    }
                }
            });
        }); 

        //Delete category
        $(document).on('click', '.deleteBtn', function(){
            let id = $(this).data('id');
            let tr = $(this).closest('tr');

            
            $('#editRow').remove();
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
                url: "{{ url('/category-delete') }}/" + id,
                method: "DELETE",
                success: function(res){
                    if(res.status === 'success'){
                        $('#deleteRow').remove(); // confirm row remove
                        loadCategories(currentPage, currentSearch);         // table refresh
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                }
            });
        });

      
    });
</script>
@endsection
