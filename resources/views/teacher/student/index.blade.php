@extends('backend.layouts.master')
@section('title', 'Teacher | Students')
@section('main-content')

<div class="container mt-4">
    <h2>Course wise students list</h2>

    <div class="d-flex justify-content-start mb-3 gap-3">
        
        <!-- Course Filter -->
        <div class="w-25">
            <select class="form-control" id="courseFilter">
                <option value="">-- Course wise students list --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <!-- Search -->
        <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search student name/email/phone...">
        </div>

    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Course Title</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody id="studentsTable">
            <tr>
                <td colspan="5" class="text-center">Loading...</td>
            </tr>
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

    function loadStudents(page = 1, search = '', course_id = '') {
        let html = '';
        $.get("{{ url('/teacher/course/students-data') }}", { page: page, search: search, course_id: course_id }, function(res){
            if(res.data.length > 0){
                $.each(res.data, function(index, student){
                    html += `<tr>
                        <td>${res.from + index}</td>
                        <td>${student.course_title}</td>
                        <td>${student.name}</td>
                        <td>${student.email}</td>
                        <td>${student.phone}</td>
                    </tr>`;
                });
            }else{
                html = `<tr><td colspan="5" class="text-center">No Student found</td></tr>`;
            }

            $('#studentsTable').html(html);

            // pagination
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

    // Initial load
    loadStudents();

    // Live search
    let typingTimer;
    $("#searchBox").on("keyup", function(){
        clearTimeout(typingTimer);
        let value = $(this).val();
        let course = $("#courseFilter").val();
        typingTimer = setTimeout(function(){
            loadStudents(1, value, course);
        }, 300);
    });

    // Course filter change
    $("#courseFilter").on("change", function(){
        let value = $("#searchBox").val();
        let course = $(this).val();
        loadStudents(1, value, course);
    });

    // Pagination click
    $(document).on("click", "#paginationLinks a", function(e){
        e.preventDefault();
        let page = $(this).data("page");
        let search = $("#searchBox").val();
        let course = $("#courseFilter").val();
        if(page) loadStudents(page, search, course);
    });

});
</script>
@endsection
