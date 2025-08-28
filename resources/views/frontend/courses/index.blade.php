@extends('backend.layouts.master')
@section('title', 'Home | Course')
@section('main-content')


  <div class="container my-5">
    <div class="row g-4">


    @foreach ($courses as $course)
        

      <!-- Course Card Start -->
      <div class="col-md-4">
        <div class="card course-card h-100 position-relative">
          <span class="badge-category"><i class="fas fa-layer-group"></i> {{ $course->category?->name }}</span>
          <img src="{{ $course->image_show }}" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $course->title }}</h5>
            <p class="teacher-name"><i class="fas fa-chalkboard-teacher"></i> {{ $course->teacher?->name ?? 'Niaz Ahmed Nayeem'}}</p>
            <p class="students-count"><i class="fas fa-user-graduate"></i> 120 Students</p>
            <p class="price">Price: 
              <span class="original-price">{{ $course->price }} TK</span>
              Discount Price: <span class="text-success">{{ $course->final_price }} TK</span>
            </p>
            <div class="mt-auto d-flex gap-2">
                <form action="{{ route('frontend.add_to_cart' , $course->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary w-50">Add to Cart</button>
                </form>
              
              <a href="{{ route('frontend.checkout', $course->slug) }}" class="btn btn-primary w-50">Buy Now</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Course Card End -->
    @endforeach

    </div>
  </div>



@endsection
