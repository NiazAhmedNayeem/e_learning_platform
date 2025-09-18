@extends('backend.layouts.master')
@section('title', 'Admin | Notices')
@section('main-content')

<div class="container mt-4">
    <h2>All Notices</h2>

    <div class="d-flex justify-content-between mb-3">
        <!-- Search (left) -->
        <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search by title...">
        </div>

        <!-- Add Button (right) -->
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                Add New Notice
            </button>
        </div>

    </div>

    

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Notice Title</th>
                <th>Creator</th>
                <th>Targeted User</th>
                <th>State Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="noticeTable">
            
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <nav>
        <ul class="pagination" id="paginationLinks"></ul>
    </nav>
</div>



<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <!-- Modal Header -->
      <div class="modal-header bg-gradient bg-primary text-white">
        <h5 class="modal-title fw-bold" id="addNoticeModalLabel">
          <i class="bi bi-megaphone-fill me-2"></i> Create Notice
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Form -->
      <form id="addNoticeForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-4">

            <!-- Title -->
            <div class="col-md-12">
              <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control shadow-sm" placeholder="Enter notice title" >
                <div class="titleError" style="color:red;"></div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea type="text" name="description" rows="3" class="form-control shadow-sm" placeholder="Write notice details..."></textarea>
                <div class="descriptionError" style="color:red;"></div>
            </div>

            <!-- Target Role -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Target Role <span class="text-danger">*</span></label>
              <select name="target_role" class="form-select shadow-sm" required>
                <option value="all">All Users</option>
                <option value="admin">Admins</option>
                <option value="teacher">Teachers</option>
                <option value="student">Students</option>
              </select>
              <div class="target_roleError" style="color:red;"></div>
            </div>

            <!-- Target Course -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Target Course (Only for Students)</label>
              <select name="target_course_id" class="form-select shadow-sm">
                <option value="">-- Select Course --</option>
                @foreach ($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
              </select>
            </div>

            <!-- Start Time -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
              <input type="datetime-local" name="start_at" class="form-control shadow-sm" >
                <div class="start_atError" style="color:red;"></div>
            </div>

            <!-- End Time -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">End Time</label>
              <input type="datetime-local" name="end_at" class="form-control shadow-sm">
            </div>

            <!-- Attachments -->
            <div class="col-md-12">
              <label class="form-label fw-semibold">Attachments (Files / Images)</label>
              <input type="file" name="attachments[]" multiple class="form-control shadow-sm">
              <div class="attachmentsError" style="color:red;"></div>
            </div>

            <!-- Status -->
            <div class="col-md-6">
              <label for="status" class="form-label fw-semibold">Status</label>
              <select class="form-select shadow-sm" id="status" name="status">
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <div class="statusError" style="color:red;"></div>
            </div>

            <!-- Notice Image -->
            <div class="col-md-6">
              <label for="editImage" class="form-label fw-semibold">Notice Image</label>
              <input type="file" class="form-control shadow-sm" id="editImage" name="image">
              <div class="imageError" style="color:red;"></div>
            </div>

            <!-- Preview -->
            <div class="col-md-12 text-center">
              <img id="addPreviewImage" src="#" alt="Preview"
                   class="img-thumbnail mt-2 shadow-sm border"
                   style="max-height: 120px; display: none;">
            </div>

          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save-fill me-1"></i> Create
          </button>
        </div>
      </form>
    </div>
  </div>
</div>







@endsection

@section('scripts')

<script>
    $(document).ready(function(){
      
      let currentPage = '';
      let currentSearch = '';

      function loadNotices(page = 1, search = ''){
        currentPage = page;
        currentSearch = search;

        let html = '';
        $.get("{{ url('/admin/notice/data') }}",{page,search}, function(res){
          
          if(res.data && res.data.length > 0){
            $.each(res.data, function(index, notice){
              html += `
                <tr>
                  <td>${res.from + index}</td>
                  <td>
                    <div class="d-flex align-items-center">
                        <img src="${notice.image_show}" 
                            class="rounded-circle shadow-sm me-2" 
                            alt="notice Image" width="40" height="40">
                        <span>${notice.title}</span>
                    </div>
                  </td>
                  <td>
                    ${notice.user 
                        ? (() => {
                            let roleName = '';
                            if(notice.user.role === 'admin'){
                                roleName = (notice.user.is_super == 1) ? 'Super Admin' : 'Admin';
                            } else if(notice.user.role === 'teacher'){
                                roleName = 'Teacher';
                            } else {
                                roleName = notice.user.role.charAt(0).toUpperCase() + notice.user.role.slice(1);
                            }
                            return `${notice.user.name} (${roleName})`;
                        })()
                        : 'N/A'
                    }
                  </td>
                  <td>
                      ${notice.target_role === 'all' ? '<span class="badge bg-info">All</span>'
                      : notice.target_role === 'admin' ? '<span class="badge bg-warning">Admin</span>'
                      : notice.target_role === 'teacher' ? '<span class="badge bg-info">Teacher</span>'
                      : notice.target_role === 'student' ? '<span class="badge bg-success">Student</span>'
                      : '<span class="badge bg-secondary">Unknown</span>'}
                  
                  </td>
                  <td>${dayjs(notice.start_at).tz('Asia/Dhaka').format('DD MMM YYYY - hh:mm A')}</td>
                  <td>${notice.end_at ? dayjs(notice.end_at).tz('Asia/Dhaka').format('DD MMM YYYY - hh:mm A') : 'N/A'}</td>
                  <td>
                      ${notice.status === 'active' ? '<span class="badge bg-success">Active</span>'
                      : notice.status === 'inactive' ? '<span class="badge bg-warning">Inactive</span>'
                      : '<span class="badge bg-secondary">Unknown</span>'}
                  </td>
                  <td>
                      <button type="button" class="btn btn-info btn-sm editBtn" data-id="${notice.id}">Edit</button>
                      <button class="btn btn-danger btn-sm deleteBtn" data-id="${notice.id}">Delete</button>
                  </td>
                </tr>
              `;
            });
          }else{
            html = `<tr><td colspan="5" class="text-center">No Video found</td></tr>`;
          }
          $('#noticeTable').html(html);

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
      loadNotices()

      // Pagination new data load 
        $(document).on("click", "#paginationLinks a", function(e){
            e.preventDefault();
            let page = $(this).data("page");
            if(page) loadNotices(page, currentSearch);
        });

        //live search
        let typingTimer;
        $("#searchBox").on("keyup", function(){
            clearTimeout(typingTimer);
            let value = $(this).val();
            typingTimer = setTimeout(function(){
                loadNotices(1, value); // before search page = 1
            }, 300); // 300ms delay (user typing)
        });
        



      $('#addNoticeForm').submit(function(e){
          e.preventDefault();

          let formData = new FormData(this);

          $.ajax({
              url: "{{ url('/admin/notice/store') }}",
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              success: function(res) {
                if(res.status === 'success') {
                    loadNotices();
                    let modalEl = document.getElementById('addNoticeModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);

                    if(!modal) {
                        modal = new bootstrap.Modal(modalEl);
                    }

                    modal.hide();

                    // backdrop remove
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');

                    // form reset
                    $('#addNoticeForm').trigger('reset');

                    toastr.success(res.message, 'Success', {timeOut: 3000});

                    
                  }
              },

              error: function(xhr){
                  if(xhr.status == 422){
                        console.log(xhr.responseText);
                      let errors = xhr.responseJSON.errors;
                      $('.titleError').html(errors.title ? errors.title[0] : '');
                      $('.descriptionError').html(errors.description ? errors.description[0] : '');
                      $('.target_roleError').html(errors.target_role ? errors.target_role[0] : '');
                      $('.start_atError').html(errors.start_at ? errors.start_at[0] : '');
                      $('.attachmentsError').html(errors.attachments ? errors.attachments[0] : '');
                      $('.statusError').html(errors.status ? errors.status[0] : '');
                      $('.imageError').html(errors.image ? errors.image[0] : '');
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
                        Are you sure you want to delete this notice? 
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
                url: "{{ url('/admin/notice/delete') }}/" + id,
                method: "DELETE",
                success: function(res){
                    if(res.status === 'success'){
                        $('#deleteRow').remove(); // confirm row remove
                        loadNotices(currentPage, currentSearch);         // table refresh
                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                }
            });
        });



    });
</script>


@endsection