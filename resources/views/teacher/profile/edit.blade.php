@extends('backend.layouts.master')
@section('title', 'Teacher Profile')
@section('main-content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header text-center py-3" style="background-color: #212529">
                    <h3 class="text-white mb-0">
                        Update Teacher Profile Info
                    </h3>
                </div>

                <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-0">
                        <!-- Left Side -->
                        <div class="col-md-4 bg-light  p-4 d-flex flex-column align-items-center justify-content-start">

                            <!-- Profile Image -->
                            <div class="mb-4">
                                @if($teacher->image)
                                    <img src="{{ $teacher->image_show }}" 
                                        alt="Profile Image" 
                                        class="rounded-circle img-thumbnail shadow-sm" 
                                        style="width: 160px; height: 160px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&size=160" 
                                        class="rounded-circle img-thumbnail shadow-sm" 
                                        alt="Default Avatar">
                                @endif
                            </div>

                            <!-- Upload Image -->
                            <div class="mb-3 w-100">
                                <label for="image" class="form-label fw-bold">Change Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Name -->
                            <div class="mb-3 w-100">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $teacher->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Gender -->
                            <div class="mb-3 w-100">
                                <label class="form-label fw-bold">Gender</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                        {{ old('gender', $teacher->gender) == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                                        {{ old('gender', $teacher->gender) == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="col-md-8 p-4">
                            <div class="row">

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" id="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        value="{{ old('email', $teacher->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold">Phone</label>
                                    <input type="text" name="phone" id="phone" 
                                        class="form-control @error('phone') is-invalid @enderror" 
                                        value="{{ old('phone', $teacher->phone) }}">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Expertise Category -->
                                <div class="col-md-6 mb-3">
                                    <label for="expertise_category_id" class="form-label fw-bold">Expertise Category</label>
                                    <select name="expertise_category_id" id="expertise_category_id" 
                                            class="form-select @error('expertise_category_id') is-invalid @enderror">
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('expertise_category_id', $teacher->expertise_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('expertise_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Profession -->
                                <div class="col-md-6 mb-3">
                                    <label for="profession" class="form-label fw-bold">Profession</label>
                                    <input type="text" name="profession" id="profession" 
                                        class="form-control @error('profession') is-invalid @enderror" 
                                        value="{{ old('profession', $teacher->profession) }}">
                                    @error('profession') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label fw-bold">Address</label>
                                    <input type="text" name="address" id="address" 
                                        value="{{ old('address', $teacher->address) }}" 
                                        class="form-control @error('address') is-invalid @enderror">
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Bio -->
                                <div class="col-md-12 mb-3">
                                    <label for="bio" class="form-label fw-bold">Bio</label>
                                    <textarea name="bio" id="bio" rows="2" 
                                            class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $teacher->bio) }}</textarea>
                                    @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Submit -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('teacher.profile') }}" class="btn btn-danger btn-lg shadow-sm">Cancel</a>
                                    <button type="submit" class="btn btn-success btn-lg shadow-sm">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection
