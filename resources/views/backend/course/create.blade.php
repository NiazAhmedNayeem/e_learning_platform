@extends('backend.layouts.master')
@section('title', 'Admin | Admin Create Course')
@section('main-content')

<div class="card card-body shadow mt-5">
    <h1 class="text-2xl font-bold text-center mt-4">Add New Course</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form style="padding: 10px" method="POST" action="{{ route('admin.course.store') }}" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <h3 class="text-lg font-semibold mb-3">Course Information</h3>

        <div class="row card-body border rounded mb-4">

            <div class="col-md-8 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
            </div>


            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Price</label>
                <input type="number" name="price" value="{{ old('price') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Discount (%)</label>
                <input type="number" name="discount" value="{{ old('discount') }}" class="form-control">
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Course Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="col-md-12 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Prerequisite</label>
                <textarea type="text" rows="5" name="prerequisite" value="{{ old('prerequisite') }}" class="form-control summernote"></textarea>
            </div>

            <div class="col-md-12 mb-4">
                <label class="block text-gray-700  font-bold">Short Description</label>
                <textarea type="text" name="short_description" value="{{ old('short_description') }}" class="form-control summernote" required></textarea>
            </div>

            <div class="col-md-12 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Long Description</label>
                <textarea type="text" rows="5" name="long_description" class="form-control summernote" required>{{ old('long_description') }}</textarea>
            </div>
            
            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Status</label>
                <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>


        </div>

        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary px-4 py-2 font-bold rounded focus:outline-none focus:shadow-outline">
                Create Course
            </button>
        </div>

    </form>
</div>
@endsection
