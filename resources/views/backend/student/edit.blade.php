@extends('backend.layouts.master')
@section('title', 'Admin | Edit Student')
@section('main-content')


    <h1 class="text-2xl font-bold text-center mt-4">Edit Student</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.student.update', $student->id ) }}" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        
        <h3 class="text-lg font-semibold mb-3">🎓 Student Information</h3>

        <div class="row card-body border rounded mb-4">

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Name</label>
                <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Email</label>
                <input type="email" name="email" value="{{ $student->email }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Phone</label>
                <input type="text" name="phone" value="{{ $student->phone }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Class</label>
                <select name="class" class="form-control" required>
                    <option value="">Select Class</option>
                    <option value="Class 1" {{ $student->class == 'Class 1' ? 'selected' : '' }}>Class 1</option>
                    <option value="Class 2" {{ $student->class == 'Class 2' ? 'selected' : '' }}>Class 2</option>
                    <option value="Class 3" {{ $student->class == 'Class 3' ? 'selected' : '' }}>Class 3</option>
                    <option value="Class 4" {{ $student->class == 'Class 4' ? 'selected' : '' }}>Class 4</option>
                    <option value="Class 5" {{ $student->class == 'Class 5' ? 'selected' : '' }}>Class 5</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Section</label>
                <select name="section" class="form-control">
                    <option value="">Select Section</option>
                    <option value="A" {{ $student->section == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $student->section == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ $student->section == 'C' ? 'selected' : '' }}>C</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Date of Birth</label>
                <input type="date" name="dob" value="{{ $student->dob }}" class="form-control">
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Age</label>
                <input type="number" name="age" value="{{ $student->age }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Pre School</label>
                <input type="text" name="pre_school" value="{{ $student->pre_school }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Pre Class</label>
                <input type="text" name="pre_class" value="{{ $student->pre_class }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Pre Section</label>
                <input type="text" name="pre_section" value="{{ $student->pre_section }}" class="form-control" required>
            </div>

            @if($student->image)
                <div class="col-md-2 mb-4">
                    <img src="{{ $student->image_show }}" width="80" height="80" alt="Student Image">
                </div>
            @endif


            <div class="col-md-2 mb-4">
                <label class="block text-gray-700 text-sm font-bold">Student Image</label>
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
