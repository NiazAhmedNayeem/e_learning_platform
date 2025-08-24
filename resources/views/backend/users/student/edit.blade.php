@extends('backend.layouts.master')
@section('title', 'Admin | Admin Update Student')
@section('main-content')


    <h1 class="text-2xl font-bold text-center mt-4">Update Student</h1>

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
            <img src="{{ $student->image_show }}" alt="Profile Image" width="100" height="100" class="rounded shadow">
        </div>
    </div>

    <form method="POST" action="{{ route('admin.student.update', $student->id) }}" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <h3 class="text-lg font-semibold mb-3">Edit Student Information</h3>

        <div class="row card-body border rounded mb-4">

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Name</label>
                <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Email</label>
                <input type="email" name="email" value="{{ $student->email }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Password</label>
                <input type="text" name="password"  class="form-control">
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Phone</label>
                <input type="text" name="phone" value="{{ $student->phone }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select Status</option>
                    <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>


            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Profession</label>
                <input type="text" name="profession" value="{{ $student->profession }}" class="form-control" required>
            </div>

            <div class="col-md-8 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Address</label>
                <input type="text" name="address" value="{{ $student->address }}" class="form-control" required>
            </div>

            

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Status</label>
                <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="1" {{ $student->status == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $student->status == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>


            <div class="col-md-8 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Bio</label>
                <textarea type="text" name="bio"class="form-control">{{ $student->bio }}</textarea>  
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Profile Image</label>
                <input type="file" name="image" class="form-control">
            </div>


        </div>

        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary px-4 py-2 font-bold rounded focus:outline-none focus:shadow-outline">
                Update Student
            </button>
        </div>

    </form>

@endsection
