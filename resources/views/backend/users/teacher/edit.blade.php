@extends('backend.layouts.master')
@section('title', 'Admin | Admin Edit Teacher')
@section('main-content')


    <h1 class="text-2xl font-bold text-center mt-4">Edit Teacher</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <img src="{{ $teacher->image_show }}" alt="Profile Image" width="100" height="100" class="rounded shadow">
        </div>
    </div>


    <form method="POST" action="{{ route('admin.update.teacher', $teacher->id) }}" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <h3 class="text-lg font-semibold mb-3">Edit Teacher Information</h3>

        <div class="row card-body border rounded mb-4">

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Name</label>
                <input type="text" name="name" value="{{ $teacher->name }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Email</label>
                <input type="email" name="email" value="{{ $teacher->email }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Password</label>
                <input type="text" name="password" class="form-control">
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Phone</label>
                <input type="text" name="phone" value="{{ $teacher->phone }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select Status</option>
                    <option value="male" {{ $teacher->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $teacher->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $teacher->gender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Expert</label>
                <select name="expertise_category_id" class="form-control">
                    <option value="">Select Expert</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $teacher->expertise_category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Profession</label>
                <input type="text" name="profession" value="{{ $teacher->profession }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Address</label>
                <input type="text" name="address" value="{{ $teacher->address }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Profile Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Status</label>
                <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="1" {{ $teacher->status == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $teacher->status == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>


            <div class="col-md-8 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Bio</label>
                <textarea type="text" name="bio" class="form-control">{{ $teacher->bio }}</textarea>  
            </div>



        </div>

        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary px-4 py-2 font-bold rounded focus:outline-none focus:shadow-outline">
                Update Teacher
            </button>
        </div>

    </form>

@endsection
