@extends('backend.layouts.master')
@section('title', 'Teacher | Course videos')
@section('main-content')

<div class="container mt-4">
    <!-- Breadcrumb -->
    {{-- @php
        if (auth()->user()->role === 'admin') {
            $dashboard = 'admin.dashboard';
        }
    @endphp
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light rounded-3 p-2">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i> <a href="{{ route($dashboard) }}" class="text-decoration-none"> Dashboard</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.course.index') }}" class="text-decoration-none ">Courses</a>
            </li>
            <li class="breadcrumb-item active " aria-current="page">{{ $course->title }} / videos</li>
        </ol>
    </nav> --}}


    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
        <h2 class="mb-0">All videos of {{ $course->title }}</h2>
    </div>


    <div class="d-flex justify-content-between mb-3">
        <!-- Search (left) -->
        <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search with video title...">
        </div>


        <!-- Add Button (right) -->
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVideoModal">
                Add New Video
            </button>
        </div>

    </div>

    

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Position</th>
                <th>Title</th>
                <th>Demo</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="videosTable">
            
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <nav>
        <ul class="pagination" id="paginationLinks"></ul>
    </nav>
</div>

<!-- Add Video Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="addVideoModalLabel">Add New Course Video for {{ $course->title }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="addVideoForm">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="row">
                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Video Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter video title" >
                    <div id="titleError" class="text-danger small"></div> 
                </div>

                <!-- Video Link -->
                <div class="mb-3">
                    <label class="form-label">Video Link</label>
                    <input type="text" name="video_link" class="form-control" placeholder="https://youtube.com/..." >
                    <div id="linkError" class="text-danger small"></div> 
                </div>


                <!-- Is Demo -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Is Demo?</label>
                    <select name="is_demo" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <div id="demoError" class="text-danger small"></div> 
                </div>

                <!-- Position -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Position</label>
                    <input type="number" name="position" class="form-control" value="1" min="1" placeholder="Video position? 1,2,3,...n">
                    <div id="positionError" class="text-danger small"></div> 
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div id="statusError" class="text-danger small"></div> 
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                    <div id="descriptionError" class="text-danger small"></div> 
                </div>

                
            </div>
            <!-- Submit -->
            <div class="text-end">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add Video</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Edit Video Modal -->
<div class="modal fade" id="editVideoModal" tabindex="-1" aria-labelledby="editVideoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="editVideoModalLabel">Edit Course Video</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editVideoForm">
            @csrf
             <input type="hidden" id="editVideoId" name="id">
            <div class="row">
                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Video Title</label>
                    <input type="text" name="title" id="editTitle" class="form-control" placeholder="Enter video title">
                    <div id="editTitleError" class="text-danger small"></div>
                </div>

                <!-- Video Link -->
                <div class="mb-3">
                    <label class="form-label">Video Link</label>
                    <input type="text" name="video_link" id="editVideoLink" class="form-control" placeholder="https://youtube.com/...">
                    <div id="editLinkError" class="text-danger small"></div>
                </div>

                <!-- Is Demo -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Is Demo?</label>
                    <select name="is_demo" id="editIsDemo" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <div id="editDemoError" class="text-danger small"></div>
                </div>

                <!-- Position -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Position</label>
                    <input type="number" name="position" id="editPosition" class="form-control" min="1">
                    <div id="editPositionError" class="text-danger small"></div>
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div id="editStatusError" class="text-danger small"></div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                    <div id="editDescriptionError" class="text-danger small"></div>
                </div>

            </div>
            <div class="text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Video</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="videoModalLabel">Video Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <iframe id="videoFrame" width="100%" height="400" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</div>



@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        let courseId = "{{ $course->id }}"; // hidden course id

        function loadVideos(page = 1, search = '') {
            let html = '';
            $.get("{{ url('teacher/course/video-data') }}/" + courseId + "?page=" + page + "&search=" + search, function(res){
                if(res.data && res.data.length > 0){
                    $.each(res.data, function(index, video){
                        html += `<tr>
                            <td>${res.from + index}</td>
                            <td>${video.position}</td>
                            <td>${video.title}</td>
                            <td>${video.is_demo == 1 ? 'Yes' : 'No'}</td>
                            <td>
                                <span class="badge ${video.status == 'active' ? 'bg-success' : 'bg-danger'}">
                                    ${video.status}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm viewVideoBtn" data-link="${video.video_link}" data-title="${video.title}">
                                    View
                                </button>
                                <button class="btn btn-warning btn-sm editBtn" data-id="${video.id}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteBtn" data-id="${video.id}">Delete</button>
                            </td>
                        </tr>`;
                    });
                }else{
                    html = `<tr><td colspan="6" class="text-center">No Video found</td></tr>`;
                }
                $('#videosTable').html(html);

                // pagination build
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

        loadVideos();

        function getSearchValue() {
            return $("#searchBox").val();
        }

        // live search
        let typingTimer;
        $("#searchBox").on("keyup", function(){
            clearTimeout(typingTimer);
            let value = $(this).val();
            typingTimer = setTimeout(function(){
                loadVideos(1, value);
            }, 300);
        });

        // Pagination new data load 
        $(document).on("click", "#paginationLinks a", function(e){
            e.preventDefault();
            let page = $(this).data("page");
            let search = $("#searchBox").val();
            if(page) loadVideos(page, search);
        });

        $('#addVideoForm').submit(function(e){
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ url('/teacher/course/video-store') }}",
                method: "POST",
                data: formData,
                processData: false, 
                contentType: false, 
                success: function(res){
                    //console.log(res)
                    if(res.status === 'success'){   
                        let modal = bootstrap.Modal.getInstance(document.getElementById('addVideoModal'));
                        modal.hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');

                        $('#addVideoForm')[0].reset(); 
                        loadVideos(); 
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                },
                error: function(xhr){
                    //console.log(xhr.responseText); // debug
                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        $('#titleError').html(errors.title ? errors.title[0] : '');
                        $('#linkError').html(errors.video_link ? errors.video_link[0] : '');
                        $('#demoError').html(errors.is_demo ? errors.is_demo[0] : '');
                        $('#positionError').html(errors.position ? errors.position[0] : '');
                        $('#statusError').html(errors.status ? errors.status[0] : '');
                    } else {
                        alert("Something went wrong: " + xhr.status);
                    }
                }

            });
        });


        $(document).on('click', '.editBtn', function(){
            let videoId = $(this).data('id');

            $.get("{{ url('/teacher/course/video-edit') }}/" + videoId, function(res){
                if(res.status === 'success'){
                    let video = res.data;
                    $('#editVideoId').val(video.id);
                    $('#editTitle').val(video.title);
                    $('#editVideoLink').val(video.video_link);
                    $('#editPosition').val(video.position);
                    $('#editIsDemo').val(video.is_demo);
                    $('#editStatus').val(video.status);
                    $('#editDescription').val(video.description);

                    let modal = new bootstrap.Modal(document.getElementById('editVideoModal'));
                        modal.show();
                    } else {
                        toastr.error(res.message);
                }
            });
        });



        $('#editVideoForm').submit(function(e){
            e.preventDefault();

            let videoId = $('#editVideoId').val();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ url('/teacher/course/video-update') }}/" + videoId,
                method: "POST",
                data: formData,
                processData: false, 
                contentType: false, 
                success: function(res){
                    //console.log(res)
                    if(res.status === 'success'){   
                        let modal = bootstrap.Modal.getInstance(document.getElementById('editVideoModal'));
                        modal.hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');

                        $('#editVideoForm')[0].reset(); 
                        loadVideos(1, getSearchValue()); 
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                },
                error: function(xhr){
                    //console.log(xhr.responseText); // debug
                    if(xhr.status == 422){
                        let errors = xhr.responseJSON.errors;
                        $('#editTitleError').html(errors.title ? errors.title[0] : '');
                        $('#editLinkError').html(errors.video_link ? errors.video_link[0] : '');
                        $('#editPositionError').html(errors.position ? errors.position[0] : '');
                        $('#editDemoError').html(errors.is_demo ? errors.is_demo[0] : '');
                        $('#editStatusError').html(errors.status ? errors.status[0] : '');
                        $('#editDescriptionError').html(errors.description ? errors.description[0] : '');
                    } else {
                        alert("Something went wrong: " + xhr.status);
                    }
                }

            });
        });


        //Delete video
        $(document).on('click', '.deleteBtn', function(){
            let id = $(this).data('id');
            let tr = $(this).closest('tr');

            
            $('#deleteRow').remove();

            // Confirm row HTML
            let deleteConfirm = `
                <tr id="deleteRow">
                    <td colspan="6" class="text-center">
                        Are you sure you want to delete this video? 
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
                url: "{{ url('/teacher/course/video-delete') }}/" + id,
                method: "DELETE",
                success: function(res){
                    if(res.status === 'success'){
                        $('#deleteRow').remove(); // confirm row remove
                        loadVideos(1, getSearchValue());  // table refresh
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                }
            });
        });

        

        // video play with modal
        $(document).on("click", ".viewVideoBtn", function () {
            let link = $(this).data("link");
            let title = $(this).data("title");

            // YouTube embed URL 
            function getYoutubeEmbedUrl(url) {
                let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
                let match = url.match(regExp);
                return (match && match[2].length === 11) 
                    ? "https://www.youtube.com/embed/" + match[2] + "?autoplay=1" 
                    : url;
            }

            $("#videoModalLabel").text(title);
            $("#videoFrame").attr("src", getYoutubeEmbedUrl(link));

            // Modal show          
            $("#videoModal").modal("show");
        });

        // when close the modal the video will stop
        $("#videoModal").on("hidden.bs.modal", function () {
            $("#videoFrame").attr("src", "");
        });




    });
</script>


@endsection