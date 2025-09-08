@extends('backend.layouts.master')
@section('title', 'All Categories')
@section('main-content')

<div class="container mt-4">
    <h2 class="mb-4">Categories List</h2>
    
   <!-- Add Button -->
<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" id="toggleAddForm">
        <i class="fas fa-plus"></i> Add New Category
    </button>
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





    <!-- Search -->
    {{-- <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.category.index') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." />
            <button class="btn btn-primary ms-2" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div> --}}

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

</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        
        //show form 
        $('#toggleAddForm').click(function(){
            $('#addCatInput').slideToggle();
        });

        function loadCategories(){
            $.get("{{ url('/categories') }}", function(res){
                let html = "";
                res.forEach(function(cat, index){
                    html += `
                        <tr>
                            <td>${index+1}</td>
                            <td>
                                <img src="${cat.image_show ?? ''}" width="50" height="50" class="rounded">
                            </td>
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

                $('#categories').html(html);
            });
        }
        loadCategories();
                              

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
            let tr = $(this).closest('tr'); // যে row তে button চাপা হয়েছে

            // আগে অন্য কোনো edit form থাকলে মুছে ফেলো
            $('#editRow').remove();

            // Ajax দিয়ে data আনো
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

                                <div class="col-md-3 d-grid">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    `;


                // সেই row এর নিচে নতুন row add করো
                tr.after(editForm);
            });
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
                        $('#editRow').remove(); // edit row সরাও
                        loadCategories();       // table reload
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

            // আগে অন্য confirm row থাকলে remove
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
                        loadCategories();         // table refresh
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                }
            });
        });

        



    });
</script>
@endsection




















{{-- 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>


    <h1>form text with error handeling</h1>

    <form id="myForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter name ...">
        <div id="errorName"></div>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email ...">
        <div id="errorEmail"></div>
        <br>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" placeholder="Enter age ...">
        <div id="errorAge"></div>
        <br>
        <button type="submit">Save</button>
    </form>

    <div id="info"></div>

    <script>
        $('#name').on('input', function(){
            $('#errorName').html('');
        });
        $('#email').on('input', function(){
            $('#errorEmail').html('');
        });
        $('#age').on('input', function(){
            $('#errorAge').html('');
        });


        $('#myForm').submit(function(e){
            e.preventDefault();
            let name = $('#name').val();
            let email = $('#email').val();
            let age = $('#age').val();
            
            $.ajax({
                url: "{{ url('/form-submit') }}",
                method: "POST",
                data: {name:name, email:email, age:age},
                success: function(res){
                    if(res.status === 'success'){
                        $('#name').val('');
                        $('#errorName').html('');
                        $('#email').val('');
                        $('#errorEmail').html('');
                        $('#age').val('');
                        $('#errorAge').html('');
                        $('#info').html('<p style="color:green;"><b>'+res.data.greeting+'</b></p>');
                        // alert(res.message);
                    }
                },
                error: function(xhr){
                    if(xhr.status == 422){
                        let errors = xhr.responseJSON.errors;
                        if(errors.name){
                            $('#errorName').html('<span style="color:red;">'+errors.name[0]+'</span>');
                        }else{
                            $('#errorName').html('');
                        }

                        if(errors.email){
                            $('#errorEmail').html('<span style="color:red;">'+errors.email[0]+'</span>');
                        }else{
                            $('#errorEmail').html('');
                        }

                        if(errors.age){
                            $('#errorAge').html('<span style="color:red;">'+errors.age[0]+'</span>');
                        }else{
                            $('#errorAge').html('');
                        }
                        
                    }
                },
            });

        });
    </script>




















    <h1>Add New Category</h1>
    <form id="addCat">
        <input id="catName" name="name" placeholder="cat name">
        <button type="submit">Add Category</button>
        <div id="errorMsg"></div>
    </form>

    <ul id="catItemList"></ul>


    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        function loadCategories(){
            $.get("{{ url('/categories') }}", function(res){
                let html = '';
                res.forEach(function(cat, i){
                    html += `
                        <li data-id="${cat.id}">
                            <b>${i+1}.</b>
                            <input class="name-input" value="${cat.name}" style="min-width:200px">
                            <span class="slug">(${cat.slug})</span>
                            <select class="status-input">
                                <option value="1" ${cat.status == 1 ? 'selected' : ''}>Active</option>
                                <option value="0" ${cat.status == 0 ? 'selected' : ''}>Inactive</option>
                            </select>
                            <button id="updateBtn">Update</button>
                            <button id="deleteBtn">Delete</button>
                        </li>`;
                });
                $('#catItemList').html(html);
            });
        }
        loadCategories();

        $('#catName').on('input', function(){
            $('#errorMsg').html('');
        })

        $('#addCat').submit(function(e){
            e.preventDefault();
            let name = $('#catName').val();
            $.ajax({
                url: "{{ url('/category-store') }}",
                method: "POST",
                data: {name:name},
                success: function(res){
                    if(res.status === 'success'){
                        $('#catName').val('');
                        $('#errorMsg').html('');
                        loadCategories();
                    }
                },
                error: function(xhr){
                    if(xhr.status === 422){
                       let errors = xhr.responseJSON.errors;
                       $('#errorMsg').html('<span style="color: red">'+errors.name[0]+'</span>');
                    }
                }
            });
        });

        $(document).on('click', '#updateBtn', function(){
            let li = $(this).closest('li');
            let id = li.data('id');
            let name = li.find('.name-input').val();
            let status = li.find('.status-input').val();

            $.ajax({
                url: "{{ url('/category-update') }}/"+id,
                method: "POST",
                data: {name:name, status:status},
                success: function(res){
                    console.log(res)
                    if(res.status === 'success'){
                        alert(res.message);
                        loadCategories();
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                    alert('Something wrong');
                },
            });
        });

        $(document).on('click', '#deleteBtn', function(){
            if(!confirm('Are you sure, you want to delete it?')) return;
            let li = $(this).closest('li');
            let id = li.data('id');
            $.ajax({
                url: "{{ url('/category-delete') }}/"+id,
                method: "DELETE",
                success: function(res){
                    if(res.status === 'success'){
                        alert(res.message);
                        loadCategories();
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                    alert('something went wrong.');
                }
            });
        });






        // $('#addCat').submit(function(e){
        //     e.preventDefault();
        //     let name = $('#catName').val();
        //     $.ajax({
        //         url: "{{ url('/category-store') }}",
        //         method: "POST",
        //         data: { name:name },
        //         success: function(res){
        //             if(res.status === 'success'){
        //                 $('#catName').val('');
        //                 loadCategories();
        //             }
                    
        //         },
        //     });
        // });


        // Update button click
// $(document).on('click', '#updateBtn', function(){
//     let li = $(this).closest('li');
//     let id = li.data('id');
//     let name = li.find('.name-input').val();
//     let status = li.find('.status-input').val();

//     $.ajax({
//         url: "{{ url('/category-update') }}/" + id,
//         method: "POST",
//         data: {
//             _token: "{{ csrf_token() }}",
//             name: name,
//             status: status
//         },
//         success: function(res){
//             if(res.status === 'success'){
//                 alert(res.message);
//                 loadCategories();
//             }
//         },
//         error: function(xhr){
//             console.log(xhr.responseText); // ✅ এখানে true error দেখতে পারবে
//             alert('Something went wrong!');
//         },
//     });
// });




    </script>





</body>
</html>

 --}}
