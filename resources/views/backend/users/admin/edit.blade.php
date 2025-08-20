@extends('backend.layouts.master')
@section('title', 'Admin | Admin Edit')
@section('main-content')


    <h1 class="text-2xl font-bold text-center mt-4">Edit Admin Info</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('user.admin.update', $admin->id) }}" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <h3 class="text-lg font-semibold mb-3">Admin Information</h3>

        <div class="row card-body border rounded mb-4">

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Name</label>
                <input type="text" name="name" value="{{ $admin->name }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Email</label>
                <input type="email" name="email" value="{{ $admin->email }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Phone</label>
                <input type="text" name="phone" value="{{ $admin->phone }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Password</label>
                <input type="text" name="password" value="" class="form-control">
            </div>

            

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Status</label>
                <select name="status" class="form-control" {{ auth()->user()->id == $admin->id ? 'disabled' : '' }}>
                    <option value="">Select Status</option>
                    <option value="1" {{ $admin->status == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $admin->status == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>


            @if($admin->image)
                <div class="col-md-2 mb-4">
                    <img src="{{ $admin->image_show }}" width="80" height="80" alt="Admin Image">
                </div>
            @endif

            <div class="col-md-2 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Profile Image</label>
                <input type="file" name="image" class="form-control">
            </div>
        </div>

        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary px-4 py-2 font-bold rounded focus:outline-none focus:shadow-outline">
                Update Admin
            </button>
        </div>

    </form>

@endsection
