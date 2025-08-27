@extends('backend.layouts.master')
@section('title', 'All admins User')
@section('main-content')

<div class="container mt-4">
    <h2>Admin User List</h2>
     <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('user.admin.create') }}" class="btn btn-primary">Add admin</a>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('user.admin.index') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
            <tr>
                {{-- <td>{{ $admin->id }}</td> --}}
                <td>{{ $loop->iteration + ($admins->currentPage()-1)*$admins->perPage() }}</td>

                <td>
                    <img src="{{ $admin->image_show }}" alt="admin Image" width="50" height="50">
                </td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->phone }}</td>
                <td>
                    @if ($admin->is_super == 1)
                        <span class="badge bg-primary">Super {{ ucfirst($admin->role) }}</span>
                    @else
                        <span class="badge bg-primary">{{ ucfirst($admin->role) }}</span>
                    @endif
                     
                </td>
                <td>
                     <span class="badge {{ $admin->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $admin->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('user.admin.edit', $admin->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @if(auth()->user()->id != $admin->id)
                        <form action="{{ route('user.admin.delete', $admin->id) }}" method="POST" style="display:inline-block;">
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
                <td colspan="10" class="text-center">No Admin Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-end">
        {{ $admins->appends(['search' => $search])->links('pagination::bootstrap-5') }}
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