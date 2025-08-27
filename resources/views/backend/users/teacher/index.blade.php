@extends('backend.layouts.master')
@section('title', 'All Teacher User')
@section('main-content')

<div class="container mt-4">
    <h2>Teacher User List</h2>
    <div class="row">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.create.teacher') }}" class="btn btn-primary">Add New Teacher</a>
        </div>
    </div>


    <div class="d-flex justify-content-start mb-3 align-items-center gap-2">
    @php $currentStatus = request('status'); @endphp

    <a href="{{ route('admin.all-teacher', array_merge(request()->except('page'), ['status' => ''])) }}"
       class="btn btn-sm {{ $currentStatus === null || $currentStatus === '' ? 'btn-primary' : 'btn-outline-primary' }}">
        All ({{ $statusCounts['all'] }})
    </a>

    <a href="{{ route('admin.all-teacher', array_merge(request()->except('page'), ['status' => '1'])) }}"
       class="btn btn-sm {{ $currentStatus === '1' ? 'btn-primary' : 'btn-outline-primary' }}">
        Active ({{ $statusCounts['active'] }})
    </a>

    <a href="{{ route('admin.all-teacher', array_merge(request()->except('page'), ['status' => '0'])) }}"
       class="btn btn-sm {{ $currentStatus === '0' ? 'btn-primary' : 'btn-outline-primary' }}">
        Inactive ({{ $statusCounts['inactive'] }})
    </a>

    <a href="{{ route('admin.all-teacher', array_merge(request()->except('page'), ['status' => '2'])) }}"
       class="btn btn-sm {{ $currentStatus === '2' ? 'btn-primary' : 'btn-outline-primary' }}">
        Pending ({{ $statusCounts['pending'] }})
    </a>

    <a href="{{ route('admin.all-teacher', array_merge(request()->except('page'), ['status' => '3'])) }}"
       class="btn btn-sm {{ $currentStatus === '3' ? 'btn-primary' : 'btn-outline-primary' }}">
        Rejected ({{ $statusCounts['rejected'] }})
    </a>

    <!-- Search Input -->
    <form class="d-flex ms-auto" method="GET" action="{{ route('admin.all-teacher') }}">
        <input type="hidden" name="status" value="{{ $currentStatus }}">
        <input class="form-control me-2" type="text" name="search" value="{{ request('search') }}" placeholder="Search..." />
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>



    

    {{-- <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.all-teacher') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div> --}}


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
                {{-- <th>Role</th> --}}
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
                {{-- <td>
                     <span class="badge bg-primary">{{ ucfirst($teacher->role) }}</span>
                </td> --}}
                <td>
                    @php
                        switch($teacher->status) {
                            case 0:
                                $statusText = 'Inactive';
                                $statusClass = 'bg-secondary';
                                break;
                            case 1:
                                $statusText = 'Active';
                                $statusClass = 'bg-success';
                                break;
                            case 2:
                                $statusText = 'Pending';
                                $statusClass = 'bg-warning text-dark';
                                break;
                            case 3:
                                $statusText = 'Rejected';
                                $statusClass = 'bg-danger';
                                break;
                            default:
                                $statusText = 'Unknown';
                                $statusClass = 'bg-dark';
                        }
                    @endphp

                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                </td>

                <td>
                    <!-- View Profile Modal Button -->
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#teacherModal{{ $teacher->id }}">
                        View
                    </button>

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




            <!-- Teacher Modal -->
            <div class="modal fade" id="teacherModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="teacherModalLabel{{ $teacher->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="teacherModalLabel{{ $teacher->id }}">Teacher Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="{{ $teacher->image_show }}" class="img-fluid rounded mb-3" alt="Profile Image">
                                </div>
                                <div class="col-md-8">
                                    <p><strong>Name:</strong> {{ $teacher->name }}</p>
                                    <p><strong>Email:</strong> {{ $teacher->email }}</p>
                                    <p><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
                                    <p><strong>Expert Category:</strong> {{ $teacher->expertCategory?->name ?? 'N/A' }}</p>
                                    <p><strong>Status:</strong> {{ $teacher->status == 2 ? 'Pending' : ($teacher->status == 1 ? 'Active' : 'Rejected') }}</p>
                                    <p><strong>Bio:</strong> {{ $teacher->bio ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer d-flex flex-wrap gap-2">
                            @if($teacher->status != 1)
                            <form action="{{ route('admin.teacher.approve', $teacher->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            @endif

                            @if($teacher->status != 0)
                            <form action="{{ route('admin.teacher.inactive', $teacher->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Inactive</button>
                            </form>
                            @endif

                            @if($teacher->status != 2)
                            <form action="{{ route('admin.teacher.pending', $teacher->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning">Pending</button>
                            </form>
                            @endif

                            @if($teacher->status != 3)
                            <form action="{{ route('admin.teacher.reject', $teacher->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </form>
                            @endif

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>


                    </div>
                </div>
            </div>
            <!-- End Modal -->


            @empty
            <tr>
                <td colspan="10" class="text-center">No Teacher Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-end">
        {{-- {{ $teachers->appends(['search' => $search])->links('pagination::bootstrap-5') }} --}}
        {{ $teachers->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

