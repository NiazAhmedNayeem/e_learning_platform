


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


