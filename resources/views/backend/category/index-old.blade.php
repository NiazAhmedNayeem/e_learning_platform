@extends('backend.layouts.master')
@section('title', 'All Categories')
@section('main-content')

<div class="container mt-4">
    <h2 class="mb-4">Categories List</h2>
    
    <!-- Button trigger Add Category Modal -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i> Add New Category
        </button>
    </div>

    <!-- Search -->
    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.category.index') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search for..." aria-label="Search for..." />
            <button class="btn btn-primary ms-2" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
            <tr>
                <td>{{ $loop->iteration + ($categories->currentPage()-1)*$categories->perPage() }}</td>
                <td>
                    <img src="{{ $category->image_show }}" alt="Category Image" class="rounded" width="50" height="50">
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                    <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $category->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <!-- Edit Button trigger modal -->
                    <button class="btn btn-warning btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editCategoryModal{{ $category->id }}">
                        Edit
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('admin.category.delete', $category->id) }}" method="POST" class="deleteForm" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm deleteBtn">Delete</button>
                    </form>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No Category Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-end">
        {{ $categories->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>


<!-- All Edit Modals  -->
@foreach ($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                    @if($category->image)
                        <small class="text-muted">Current: <img src="{{ $category->image_show }}" width="40"></small>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach


<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')

@endsection
