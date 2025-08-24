@extends('backend.layouts.master')
@section('title', 'All Teacher User')
@section('main-content')

<div class="container mt-4">
    <h2>Teacher User List</h2>
     <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.create.teacher') }}" class="btn btn-primary">Add New Teacher</a>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.all-teacher') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Teacher ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Expert</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($teachers as $teacher)
            <tr>
                {{-- <td>{{ $admin->id }}</td> --}}
                <td>{{ $loop->iteration + ($teachers->currentPage()-1)*$teachers->perPage() }}</td>

                <td>
                    <img src="{{ $teacher->image_show }}" alt="teacher Image" width="50" height="50">
                </td>
                <td>{{ $teacher->unique_id }}</td>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ $teacher->phone }}</td>
                <td>{{ $teacher->expertCategory?->name }}</td>
                <td>
                     <span class="badge bg-primary">{{ ucfirst($teacher->role) }}</span>
                </td>
                <td>
                     <span class="badge {{ $teacher->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $teacher->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.edit.teacher', $teacher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @if(auth()->user()->id != $teacher->id)
                        <form action="{{ route('admin.delete.teacher', $teacher->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">
                                Delete
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No Teacher Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-end">
        {{ $teachers->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

















{{-- 


@extends('backend.layouts.master')
@section('title', 'All admins')
@section('main-content')


    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mt-4">All admins Information</h1>
        </div>
        <div>
            <a href="{{ route('admin.admin.create') }}" class="mt-4 btn btn-primary">Add New admin</a>
        </div>
    </div>

    
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">admins List</li>
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