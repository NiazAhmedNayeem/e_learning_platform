@extends('backend.layouts.master')
@section('title', 'Teacher | Students')
@section('main-content')

<div class="container mt-4">
    <h2>Course wise students list</h2>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('teacher.course.student') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search with course title..." />
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
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
        <tbody>
            @php $sl = ($courses->currentPage() - 1) * $courses->perPage() + 1; @endphp
            @forelse ($courses as $course)
                @foreach ($course->students as $student)
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td>
                            <img src="{{ $course->image_show }}" alt="Course Image" width="50" height="50">
                            {{ $course->title }}
                        </td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center">No Student Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        {{ $courses->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
