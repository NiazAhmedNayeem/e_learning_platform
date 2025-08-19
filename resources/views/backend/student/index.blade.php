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
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Class</th>
                <th>Section</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
            <tr>
                {{-- <td>{{ $student->id }}</td> --}}
                <td>{{ $loop->iteration + ($students->currentPage()-1)*$students->perPage() }}</td>

                <td>
                    @if($student->image)
                        <img src="{{ $student->image_show }}" alt="Student Image" width="50" height="50">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->class }}</td>
                <td>{{ $student->section }}</td>
                <td>{{ ucfirst($student->gender) }}</td>
                <td>{{ $student->dob }}</td>
                <td>
                    <a href="{{ route('admin.student.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.student.delete', $student->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">Delete</button>
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

















{{-- 


@extends('backend.layouts.master')
@section('title', 'All Students')
@section('main-content')


    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mt-4">All Students Information</h1>
        </div>
        <div>
            <a href="{{ route('admin.student.create') }}" class="mt-4 btn btn-primary">Add New Student</a>
        </div>
    </div>

    
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Students List</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Primary Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Warning Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Success Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Danger Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                DataTable Example
            </div>
            <div class="card-body">
               


            </div>
        </div>

@endsection --}}