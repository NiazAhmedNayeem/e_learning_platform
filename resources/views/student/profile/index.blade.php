@extends('backend.layouts.master')
@section('title', 'Student Profile')
@section('main-content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">



            <div id="profileView">
                @include('student.profile.profile_view')
            </div>

            
            <div id="profileEdit" style="display: none">

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header text-center py-3" style="background-color: #212529">
                        <h3 class="text-white mb-0">
                            Update Student Profile Info
                        </h3>
                    </div>

                    <form  id="profileUpdateForm">
                        @csrf

                        <div class="row g-0">
                            <!-- Left Side -->
                            <div class="col-md-4 bg-light  p-4 d-flex flex-column align-items-center justify-content-start">

                                <!-- Profile Image -->
                                <div class="mb-4">
                                    @if($student->image)
                                        <img src="{{ $student->image_show }}" 
                                            alt="Profile Image" 
                                            class="rounded-circle img-thumbnail shadow-sm" 
                                            style="width: 160px; height: 160px; object-fit: cover;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&size=160" 
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
                                {{-- <div class="mb-3 w-100">
                                    <label for="name" class="form-label fw-bold">Full Name</label>
                                    <input type="text" name="name" id="name" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        value="{{ old('name', $student->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div> --}}

                                <!-- Gender -->
                                <div class="mb-3 w-100">
                                    <label class="form-label fw-bold">Gender</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                            {{ old('gender', $student->gender) == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                                            {{ old('gender', $student->gender) == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                    <div style="color:red;" id="genderError"></div>
                                </div>
                            </div>

                            <!-- Right Side -->
                            <div class="col-md-8 p-4">
                                <div class="row">

                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-bold">Name</label>
                                        <input type="text" name="name" id="name" 
                                            class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ old('name', $student->name) }}">
                                        <div style="color:red;" id="nameError"></div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" name="email" id="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            value="{{ old('email', $student->email) }}" >
                                        <div style="color:red;" id="emailError"></div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-bold">Phone</label>
                                        <input type="text" name="phone" id="phone" 
                                            class="form-control @error('phone') is-invalid @enderror" 
                                            value="{{ old('phone', $student->phone) }}">
                                        <div style="color:red;" id="phoneError"></div>
                                    </div>


                                    <!-- Profession -->
                                    <div class="col-md-6 mb-3">
                                        <label for="profession" class="form-label fw-bold">Profession</label>
                                        <input type="text" name="profession" id="profession" 
                                            class="form-control @error('profession') is-invalid @enderror" 
                                            value="{{ old('profession', $student->profession) }}">
                                        @error('profession') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label fw-bold">Address</label>
                                        <input type="text" name="address" id="address" 
                                            value="{{ old('address', $student->address) }}" 
                                            class="form-control @error('address') is-invalid @enderror">
                                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Bio -->
                                    <div class="col-md-12 mb-3">
                                        <label for="bio" class="form-label fw-bold">Bio</label>
                                        <textarea name="bio" id="bio" rows="2" 
                                                class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $student->bio) }}</textarea>
                                        @error('bio') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Submit -->
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button id="cancelEdit" class="btn btn-danger btn-lg shadow-sm">Cancel</button>
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
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){

            $(document).on('click', '#editProfileBtn', function(e){
                e.preventDefault();
                $('#profileView').hide();
                $('#profileEdit').show();
            });


            $('#cancelEdit').click(function(e){
                e.preventDefault();
                $('#profileView').show();
                $('#profileEdit').hide();
                $('#profileUpdateForm')[0].reset();
            });


            $('#profileUpdateForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ url('/student/profile/update') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res){
                        if(res.status === 'success'){
                            $('#profileView').html(res.html);
                            $('#profileView').show();
                            $('#profileEdit').hide();
                            toastr.success(res.message, 'Success', {timeOut: 3000});
                        }
                    },
                    error: function(xhr){
                        if(xhr.status == 422){
                            let errors = xhr.responseJSON.errors;
                            $('#nameError').html(errors.name ? errors.name[0] : '');
                            $('#genderError').html(errors.gender ? errors.gender[0] : '');
                            $('#emailError').html(errors.email ? errors.email[0] : '');
                            $('#phoneError').html(errors.phone ? errors.phone[0] : '');
                        }
                    },
                });
            });

            $('#name').on('input', function(){
                $('#nameError').html('');
            });
            $('#gender').on('input', function(){
                $('#genderError').html('');
            });
            $('#email').on('input', function(){
                $('#emailError').html('');
            });
            $('#phone').on('input', function(){
                $('#phoneError').html('');
            });


        });
    </script>
@endsection
