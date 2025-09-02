@extends('backend.layouts.master')
@section('title', 'Admin | Assign Course')
@section('main-content')

<div class="container mt-4">
    <h2 class="mb-4">Assigned Course List</h2>

    <!-- Add Assign Button -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignModal">
            <i class="fas fa-plus"></i> Add Assign
        </button>
    </div>

    <!-- Courses Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Title</th>
                <th>Teacher</th>
                <th>Category</th>
                <th>Status</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assign_courses as $course)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $course->image_show }}" alt="Course Image" class="rounded" width="50" height="50">
                    {{$course->title }}
                </td>
                <td>
                    <img src="{{ $course->teacher?->image_show }}" alt="Course Image" class="rounded" width="50" height="50">
                    {{ $course->teacher?->name ?? 'Not Assigned' }}
                </td>
                <td>
                    {{ $course->category?->name ?? 'N/A' }}
                </td>
                <td>
                    <span class="badge {{ $course->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $course->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <!-- Edit Button triggers modal -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAssignModal{{ $course->id }}">
                        Edit
                    </button>
                    <form action="{{ route('admin.course_assign.delete', $course->id) }}" method="POST" class="deleteForm" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm deleteBtn">Delete</button>
                    </form>
                </td>
            </tr>
        

            <!-- Edit Assign Modal -->
            <div class="modal fade" id="editAssignModal{{ $course->id }}" tabindex="-1" aria-labelledby="editAssignModalLabel{{ $course->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.course_assign.update', $course->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="editAssignModalLabel{{ $course->id }}">Edit Teacher Assignment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label>Course Title</label>
                                <p>{{ $course->title }} - ({{ $course->category?->name ?? 'N/A' }})</p>
                                <label>Select Teacher</label>
                                <select name="teacher_id" class="form-control select2-modal" style="width:100%;" required>
                                    <option value="">-- Select Teacher --</option>
                                    @php
                                        $teachers = \App\Models\User::where('role','teacher')
                                                    ->where('expertise_category_id', $course->category_id)
                                                    ->get();
                                    @endphp
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} ({{ $teacher->expertCategory?->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            @empty
            <tr>
                <td colspan="7" class="text-center">No Assigned Courses Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Assign Modal -->
<div class="modal fade" id="addAssignModal" tabindex="-1" aria-labelledby="addAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.course_assign.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addAssignModalLabel">Add Teacher Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    
                    <!-- Select Course -->
                    <div class="mb-3">
                        <label>Select Course</label>
                        <select name="course_id" id="courseSelect" class="form-control select2" style="width:100%;" required>
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $courseOption)
                                <option value="{{ $courseOption->id }}" data-category="{{ $courseOption->category_id }}">
                                    {{ $courseOption->title }} ({{ $courseOption->category?->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Teacher -->
                    <div class="mb-3">
                        <label>Select Teacher</label>
                        <select name="teacher_id" id="teacherSelect" class="form-control select2" style="width:100%;" required>
                            <option value="">-- Select Teacher --</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
$(document).ready(function() {

    // initialize select2 for modal (dropdown inside modal)
    $('#courseSelect, #teacherSelect').select2({
        dropdownParent: $('#addAssignModal')
    });

    // When course is selected → fetch teachers
    $('#courseSelect').on('change', function() {
        var categoryId = $(this).find(':selected').data('category');
        var teacherSelect = $('#teacherSelect');

        teacherSelect.empty().append('<option value="">-- Select Teacher --</option>');

        if(categoryId) {
            $.ajax({
                url: "{{ route('admin.getTeachersByCategory') }}",
                type: 'GET',
                data: { category_id: categoryId },
                success: function(data) {
                    $.each(data, function(key, teacher) {
                        teacherSelect.append('<option value="'+teacher.id+'">'+teacher.name+'</option>');
                    });
                    teacherSelect.trigger('change'); // refresh select2
                },
                error: function() {
                    alert('Teacher loading error');
                }
            });
        }
    });

});
</script>

{{-- for edit assign teacher --}}
<script>
    $(document).ready(function() {
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('.select2-modal').select2({
                dropdownParent: $(this) 
            });
        });
    });
</script>
@endsection
