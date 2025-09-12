@extends('backend.layouts.master')
@section('title', 'Admin | Video Player')
@section('main-content')


<style>
    body { background-color: #181818; color: #e5e5e5; font-family: Arial, sans-serif; }
    .video-player { background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.6); }
    .video-player video { width: 100%; height: auto; }
    .video-details { padding: 1rem; background: #202020; }
    .video-title { font-size: 1.2rem; font-weight: 600; margin-bottom: .5rem; color: #fff; }
    .video-meta { font-size: 0.9rem; color: #aaa; }
    .card-dark { background: #202020; border: none; color: #e5e5e5; }
    .video-list { max-height: 75vh; overflow-y: auto; }
    .video-list video { width: 100px; border-radius: 6px; }
    .list-group-item { background: #202020; color: #ddd; border-color: #2c2c2c; cursor: pointer; transition: background .2s; }
    .list-group-item:hover { background: #2c2c2c; }
    .list-group-item.active { background: #0d6efd !important; color: #fff !important; }
    .list-group-item.active video { border: 2px solid #fff; }
    .video-list::-webkit-scrollbar { width: 6px; }
    .video-list::-webkit-scrollbar-track { background: #202020; }
    .video-list::-webkit-scrollbar-thumb { background: #444; border-radius: 10px; }
</style>

<div class="container py-4">
  <div class="row g-3">

    <!-- Main Video Player -->
    <div class="col-lg-8">
      <div class="video-player">
        <div class="card-header bg-dark border-0 fw-semibold">Course: {{ $course->title }}</div>
        <video id="main-video" controls autoplay>
          <source src="{{ asset('videos/1.mp4') }}" type="video/mp4">
        </video>
        <div class="video-details">
          <h5 class="video-title" id="main-title">01. Web Development</h5>
          <div class="video-meta d-flex justify-content-between align-items-center">
            <span>120k views • 2 days ago</span>
            <div>
              <button class="btn btn-sm btn-outline-light me-2">👍 Like</button>
              <button class="btn btn-sm btn-outline-light">🔗 Share</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Video Playlist -->
    <div class="col-lg-4">
      <div class="card card-dark shadow-sm h-100 rounded-3">
        <div class="card-header bg-dark border-0 fw-semibold">📺 Playlist</div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush video-list">
            <li class="list-group-item d-flex align-items-center active">
              <video src="{{ asset('videos/2.mp4') }}" muted></video>
              <span class="ms-3">02. Web Development</span>
            </li>
            <li class="list-group-item d-flex align-items-center">
              <video src="{{ asset('videos/3.mp4') }}" muted></video>
              <span class="ms-3">03. Web Development</span>
            </li>
            <li class="list-group-item d-flex align-items-center">
              <video src="{{ asset('videos/4.mp4') }}" muted></video>
              <span class="ms-3">04. Beni Khuley</span>
            </li>
            <li class="list-group-item d-flex align-items-center">
              <video src="{{ asset('videos/1.mp4') }}" muted></video>
              <span class="ms-3">05. Master-D - Tumi Jaio Na ft.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection




@section('scripts')
<script>
  const listItems = document.querySelectorAll(".video-list .list-group-item");
  const mainVideo = document.getElementById("main-video");
  const mainTitle = document.getElementById("main-title");

  listItems.forEach(item => {
    item.addEventListener("click", () => {
      listItems.forEach(li => li.classList.remove("active"));
      item.classList.add("active");

      const src = item.querySelector("video").getAttribute("src");
      const text = item.querySelector("span").innerText;

      mainVideo.src = src;
      mainTitle.innerText = text;
      mainVideo.play();
    });
  });
</script>
@endsection
