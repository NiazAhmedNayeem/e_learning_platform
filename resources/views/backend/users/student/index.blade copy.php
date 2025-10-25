@extends('backend.layouts.master')
@section('title', 'All Students')
@section('main-content')

<div class="container mt-4">
    <h2>Student List</h2>
     <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.student.create') }}" class="btn btn-primary">Add Student</a>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.student.index') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    {{-- Success Message
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    {{-- @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif  --}}

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
            <tr>
                {{-- <td>{{ $student->id }}</td> --}}
                <td>{{ $loop->iteration + ($students->currentPage()-1)*$students->perPage() }}</td>

                <td>
                    <img src="{{ $student->image_show }}" alt="Student Image" width="50" height="50">
                </td>
                <td>{{ $student->unique_id }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ ucfirst($student->gender) }}</td>
                <td>
                    <a href="{{ route('admin.student.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.student.delete', $student->id) }}" method="POST" class="deleteForm" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm deleteBtn">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No Students Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-end">
        {{ $students->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection


