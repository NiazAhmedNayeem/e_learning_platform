@extends('backend.layouts.master')
@section('title', 'Teacher | Assigned Courses')
@section('main-content')

<div class="container mt-4">
    <div id="headSearch">
        <h2>Assigned Courses List</h2>

        <div class="d-flex justify-content-end mb-3">
            <form class="d-flex" method="GET" action="{{ route('teacher.assign.courses') }}">
                <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search with course title..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Students</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Final Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assign_courses as $course)
            <tr>
                <td>{{ $loop->iteration + ($assign_courses->currentPage()-1)*$assign_courses->perPage() }}</td>

                <td>
                    <img src="{{ $course->image_show }}" alt="Course Image" width="50" height="50">
                </td>
                <td>{{ $course->title }}</td>
                <td>{{ $course->category?->name }}</td>
                <td>{{ $course->students?->count() }}</td>
                <td>{{ $course->price }}</td>
                <td>{{ $course->discount }} %</td>
                <td>{{ $course->final_price }}</td>
                <td>
                    <span class="badge {{ $course->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $course->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <!-- Details Button -->
                    <button class="btn btn-warning btn-sm courseDetailsBtn" data-id="{{ $course->id }}">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <a href="{{ route('teacher.course.manage-videos', $course->id) }}" class="btn btn-success btn-sm">
                        + <i class="fa-solid fa-video"></i>
                    </a>

                    <a href="{{ route('teacher.course.video-player', $course->id) }}" class="btn btn-info btn-sm">
                        <i class="fa-solid fa-circle-play"></i>
                    </a>
                </td>
            </tr>

            <!-- Course Details Modal -->
            <div id="courseDetails-{{ $course->id }}" class="courseDetails card shadow-lg rounded-3 border-0 mt-3" style="display: none;">
                <div style="background-color: #212529" class="card-header text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Course Details</h4>
                    <span class="badge bg-warning text-dark">{{ $course->category?->name ?? 'N/A' }}</span>
                    <button class="btn btn-danger btn-sm closeDetails">⬅ Back</button>
                </div>

                <div class="card-body">
                    <div class="row align-items-start">
                        <!-- Image & Price -->
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ $course->image_show }}" alt="{{ $course->title }}" 
                                class="img-fluid rounded shadow" style="max-height: 280px; object-fit: cover;">

                            <div class="mt-4 p-3 bg-light rounded shadow-sm">
                                <h5 class="fw-bold mb-2">Pricing</h5>
                                @if($course->discount && $course->final_price < $course->price)
                                    <p><del>{{ number_format($course->price, 2) }} TK</del></p>
                                    <p class="text-danger">- {{ number_format($course->discount) }} %</p>
                                    <p class="fw-bold text-success fs-5">After Discount: {{ number_format($course->final_price, 2) }} TK</p>
                                @else
                                    <p class="fw-bold text-success fs-5">Price: {{ number_format($course->price, 2) }} TK</p>
                                @endif
                            </div>

                            <div class="mt-3">
                                <h6 class="fw-bold">Status:</h6>
                                @if($course->status == 1)
                                    <span class="badge bg-success px-3 py-2">Active</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-md-8">
                            <h4 class="fw-bold">📘 {{ $course->title }}</h4>
                            <hr>
                            <h5 class="fw-bold">📌 Short Description</h5>
                            <p class="text-muted">{!! $course->short_description !!}</p>
                            <hr>
                            <h5 class="fw-bold">📖 Long Description</h5>
                            <p>{!! $course->long_description !!}</p>
                            <hr>
                            @if(trim(strip_tags($course->prerequisite)) !== '')
                                <h5 class="fw-bold">📝 Prerequisite</h5>
                                <p>{!! $course->prerequisite !!}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer text-muted text-end">
                    Last Updated: {{ $course->updated_at->setTimezone('Asia/Dhaka')->format('d M, Y h:i A') }}
                </div>
            </div>

            @empty
            <tr>
                <td colspan="10" class="text-center">No Courses Found</td>
            </tr>
            @endforelse
        </tbody>

    </table>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-end">
        {{ $assign_courses->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>


</div>
@endsection


@section('scripts')
<script>
    $(document).on('click', '.courseDetailsBtn', function(e){
        e.preventDefault();
        let id = $(this).data('id');

        // Hide table, search, pagination
        $("table").hide();
        $("#headSearch").hide();
        $(".pagination").hide();

        // Hide all details first
        // $('.courseDetails').hide();

        // Show selected course details
        $('#courseDetails-' + id).show();
    });

    // Close button
    $(document).on('click', '.closeDetails', function(){
        $(this).closest('.courseDetails').hide();

        $("table").show();
        $("#headSearch").show();
        $(".pagination").show();
    });
</script>


@endsection