@extends('backend.layouts.master')
@section('title', 'Admin | All Courses')
@section('main-content')

<div class="container mt-4">
    <h2>Teacher User List</h2>
     <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.course.create') }}" class="btn btn-primary">Add New Course</a>
    </div>

    {{-- <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.course.index') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div> --}}

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Teacher</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Final Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courses as $course)
            <tr>
                {{-- <td>{{ $admin->id }}</td> --}}
                <td>{{ $loop->iteration + ($courses->currentPage()-1)*$courses->perPage() }}</td>

                <td>
                    <img src="{{ $course->image_show }}" alt="Course Image" width="50" height="50">
                </td>
                <td>{{ $course->title }}</td>
                <td>{{ $course->category?->name }}</td>
                <td>{{ $course->teacher?->name }}</td>
                <td>{{ $course->price }}</td>
                <td>{{ $course->discount }} %</td>
                <td>{{ $course->final_price }}</td>
                <td>
                     <span class="badge {{ $course->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $course->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.course.edit', $course->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    
                    <form action="{{ route('admin.course.delete', $course->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">
                            Delete
                        </button>
                    </form>
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
    {{-- <div class="d-flex justify-content-end">
        {{ $courses->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div> --}}
</div>
@endsection


