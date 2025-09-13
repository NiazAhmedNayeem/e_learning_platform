@extends('backend.layouts.master')
@section('title', 'Admin | Video Player')
@section('main-content')

<style>
    body { background-color: #181818; color: #e5e5e5; font-family: Arial, sans-serif; }
    .video-player { background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.6); }
    .video-player iframe { width: 100%; height: 450px; border: none; }
    .video-details { padding: 1rem; background: #202020; }
    .video-title { font-size: 1.2rem; font-weight: 600; margin-bottom: .5rem; color: #fff; }
    .card-dark { background: #202020; border: none; color: #e5e5e5; }
    .video-list { max-height: 75vh; overflow-y: auto; }
    .list-group-item { background: #202020; color: #ddd; border-color: #2c2c2c; cursor: pointer; transition: background .2s; display: flex; align-items: center; }
    .list-group-item:hover { background: #2c2c2c; }
    .list-group-item.active { background: #0d6efd !important; color: #fff !important; }
    .video-list img { width: 100px; border-radius: 6px; }
    .video-list span { margin-left: 10px; font-size: 0.95rem; }
    .video-list::-webkit-scrollbar { width: 6px; }
    .video-list::-webkit-scrollbar-thumb { background: #444; border-radius: 10px; }
</style>

<div class="container py-4">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-dark rounded-3 p-2">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-light">Dashboard</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.course.index') }}" class="text-decoration-none text-light">Courses</a>
        </li>
        <li class="breadcrumb-item active text-white" aria-current="page">{{ $course->title }} Videos</li>
    </ol>
</nav>



  <div class="row g-3">

    <!-- Main Video Player -->
    <div class="col-lg-8">
      <div class="video-player">
        <div class="card-header bg-dark border-0 fw-semibold">Course: {{ $course->title }}</div>

        @php
            $defaultVideo = $videos->where('is_demo', 1)->first() ?? $videos->first();

            

            function getYoutubeEmbedUrl($url) {
                $id = getYoutubeId($url);
                return $id ? "https://www.youtube.com/embed/{$id}?enablejsapi=1" : $url;
            }

            function getYoutubeThumbnail($url) {
                $id = getYoutubeId($url);
                return $id ? "https://img.youtube.com/vi/{$id}/mqdefault.jpg" : '';
            }
        @endphp

        @if($defaultVideo)
          <iframe id="main-video" src="{{ getYoutubeEmbedUrl($defaultVideo->video_link) }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        @else
          <iframe id="main-video" src="" allowfullscreen></iframe>
        @endif

        <div class="video-details">
          <h5 class="video-title" id="main-title">{{ $defaultVideo?->title ?? 'No Video' }}</h5>
        </div>
      </div>
    </div>

    <!-- Video Playlist -->
    <div class="col-lg-4">
      <div class="card card-dark shadow-sm h-100 rounded-3">
        <div class="card-header bg-dark border-0 fw-semibold">📺 Playlist</div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush video-list">
            @forelse ($videos as $video)
              <li class="list-group-item {{ $defaultVideo && $defaultVideo->id == $video->id ? 'active' : '' }}"
                  data-src="{{ getYoutubeEmbedUrl($video->video_link) }}"
                  data-title="{{ $video->title }}">
                <img src="{{ getYoutubeThumbnail($video->video_link) }}" alt="thumbnail">
                <span>{{ $video->title }}</span>
              </li>
              @empty
                <li class="list-group-item text-center disabled" style="cursor: default; opacity: 0.7;">
                  No videos available
                </li>
              @endforelse
          </ul>

        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const listItems = document.querySelectorAll(".video-list .list-group-item");
    const mainVideo = document.getElementById("main-video");
    const mainTitle = document.getElementById("main-title");

    // YouTube Iframe API load
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    let player;

    window.onYouTubeIframeAPIReady = function() {
      player = new YT.Player('main-video', {
        events: {
          'onStateChange': onPlayerStateChange
        }
      });
    };

    function onPlayerStateChange(event) {
      if (event.data === YT.PlayerState.ENDED) {
        playNextVideo();
      }
    }

    function playNextVideo() {
      const active = document.querySelector(".video-list .list-group-item.active");
      if (active && active.nextElementSibling) {
        active.nextElementSibling.click();
      }
    }

    listItems.forEach(item => {
      item.addEventListener("click", () => {
        listItems.forEach(li => li.classList.remove("active"));
        item.classList.add("active");

        const src = item.getAttribute("data-src");
        const title = item.getAttribute("data-title");

        mainVideo.src = src;
        mainTitle.innerText = title;

        if (player && player.loadVideoById) {
          const videoId = src.split("/embed/")[1].split("?")[0];
          player.loadVideoById(videoId);
        }
      });
    });
  });
</script>
@endsection
