@extends('backend.layouts.master')
@section('title', 'Student | My Courses')
@section('main-content')

<div class="container mt-4">
    <h2>My Courses List</h2>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('student.courses') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search with course title..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Course Title</th>
                <th>Category</th>
                <th>Teacher</th>
                {{-- <th>Price</th>
                <th>Discount</th>
                <th>Final Price</th> --}}
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courseItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    <img src="{{ $item->course?->image_show }}" alt="Course Image" width="50" height="50">
                    {{ $item->course->title }}
                </td>
                <td>{{ $item->course?->category?->name }}</td>
                <td>
                    <img src="{{ $item->course?->teacher?->image_show }}" alt="Course Image" width="50" height="50">
                    {{ $item->course?->teacher?->name }}
                </td>
                {{-- <td>{{ $item->course?->price }}</td>
                <td>{{ $item->course?->discount }} %</td>
                <td>{{ $item->course?->final_price }}</td> --}}
                <td>
                     @php
                        $status = $item->order?->status;
                        $badgeClass = match($status) {
                            'approved' => 'bg-success',
                            'pending' => 'bg-secondary',
                            'rejected' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        $statusText = ucfirst($status ?? '');
                    @endphp

                    <span class="badge {{ $badgeClass }}">
                        {{ $statusText }}
                    </span>

                </td>
                <td>
                    <a href="{{ route('student.course.details', $item->course?->slug) }}" class="btn btn-warning btn-sm">View</a>
                    
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
        {{ $courseItems->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection


