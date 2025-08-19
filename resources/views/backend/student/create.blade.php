@extends('backend.layouts.master')
@section('title', 'Admin | Add New Student')
@section('main-content')


    <h1 class="text-2xl font-bold text-center mt-4">Add New Student</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.student.store') }}" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <h3 class="text-lg font-semibold mb-3">🎓 Student Information</h3>

        <div class="row card-body border rounded mb-4">

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Class</label>
                <select name="class" class="form-control" required>
                    <option value="">Select Class</option>
                    <option value="Class 1" {{ old('class') == 'Class 1' ? 'selected' : '' }}>Class 1</option>
                    <option value="Class 2" {{ old('class') == 'Class 2' ? 'selected' : '' }}>Class 2</option>
                    <option value="Class 3" {{ old('class') == 'Class 3' ? 'selected' : '' }}>Class 3</option>
                    <option value="Class 4" {{ old('class') == 'Class 4' ? 'selected' : '' }}>Class 4</option>
                    <option value="Class 5" {{ old('class') == 'Class 5' ? 'selected' : '' }}>Class 5</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Section</label>
                <select name="section" class="form-control">
                    <option value="">Select Section</option>
                    <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('section') == 'C' ? 'selected' : '' }}>C</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Date of Birth</label>
                <input type="date" name="dob" value="{{ old('dob') }}" class="form-control">
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Age</label>
                <input type="number" name="age" value="{{ old('age') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Pre School</label>
                <input type="text" name="pre_school" value="{{ old('pre_school') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Pre Class</label>
                <input type="text" name="pre_class" value="{{ old('pre_class') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Pre Section</label>
                <input type="text" name="pre_section" value="{{ old('pre_section') }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Image</label>
                <input type="file" name="image" class="form-control">
            </div>
        </div>

        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary px-4 py-2 font-bold rounded focus:outline-none focus:shadow-outline">
                Create Student
            </button>
        </div>

    </form>

@endsection
