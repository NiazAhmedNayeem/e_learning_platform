@extends('backend.layouts.master')
@section('title', 'Teacher | Assigned Courses')
@section('main-content')

<div class="container mt-4">
    <h2>Assigned Courses List</h2>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('teacher.assign.courses') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search with course title..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
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
                {{-- <td>{{ $admin->id }}</td> --}}
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
                    <a href="{{ route('teacher.assign.course.details', $course->slug) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-eye"></i></a>
                    <a href="{{ route('teacher.course.manage-videos', $course->id) }}" class="btn btn-success btn-sm">+<i class="fa-solid fa-video"></i></a>
                    <a href="{{ route('teacher.course.video-player', $course->id) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-circle-play"></i></a>
                    
                </td>
            </tr>
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


