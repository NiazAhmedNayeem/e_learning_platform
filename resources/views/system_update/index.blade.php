@extends('backend.layouts.master')
@section('title', 'App Update')
@section('main-content')

    <div class="app-content-area">
        <div class="container-fluid">

            <div class="container py-5">

                {{-- Header --}}
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-primary">App Update</h2>
                    <p class="text-muted">Managed by <span class="fw-semibold">{{ auth()->user()->name }}</span></p>
                </div>

                {{-- Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Steps --}}
                <div class="row g-4 mb-5">

                    {{-- Step 1: Create Backup --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-warning text-dark fw-bold">Step 1: Create Backup</div>
                            <div class="card-body pt-3 d-flex flex-column justify-content-between">
                                <p class="text-muted">Recommended before updating your app. This will create a ZIP of app,
                                    public,
                                    resources, and routes.</p>
                                <form action="{{ route('admin.update.backup') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ env('UPDATE_API_TOKEN') }}">
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="bi bi-cloud-download-fill me-2"></i> Create & Download Backup
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Upload Update ZIP --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-primary text-white fw-bold">Step 2: Upload Update ZIP</div>
                            <div class="card-body pt-3 d-flex flex-column justify-content-between">
                                <form action="{{ route('admin.update.upload') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="update_zip" class="form-control" accept=".zip"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-upload me-2"></i> Upload ZIP
                                    </button>
                                </form>

                                @if ($uploaded)
                                    <div class="mt-3 small text-muted text-center">
                                        Uploaded ZIP: <strong>{{ $uploaded }}</strong>
                                    </div>
                                @else
                                    <div class="mt-3 small text-muted text-center">No uploaded ZIP found.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Apply Update --}}
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white fw-bold">Step 3: Apply Update</div>
                            <div class="card-body pt-3 text-center">
                                <p class="text-muted mb-3">A safety backup will be created automatically before applying the
                                    update.
                                </p>
                                <form action="{{ route('admin.update.run') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ env('UPDATE_API_TOKEN') }}">

                                    <style>
                                        @keyframes spin {
                                            0% {
                                                transform: rotate(0deg);
                                            }

                                            100% {
                                                transform: rotate(360deg);
                                            }
                                        }

                                        .spin {
                                            display: inline-block;
                                            animation: spin 0.8s linear infinite;
                                        }
                                    </style>

                                    <button id="updateBtn" type="submit" class="btn btn-success btn-lg px-5">
                                        <i id="updateIcon" class="bi bi-arrow-repeat me-2"></i>
                                        <span id="updateText">Update Now</span>
                                    </button>


                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Backups Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header  bg-secondary text-white fw-bold">Backups</div>
                    <div class="card-body pt-3">
                        @if (count($backups))
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Backup Name</th>
                                            <th>Date & Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($backups as $b)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.update.backup.download', $b['name']) }}"
                                                        class="text-decoration-none fw-semibold">
                                                        <i class="bi bi-download me-1"></i>{{ $b['name'] }}
                                                    </a>
                                                </td>
                                                <td>{{ $b['time'] }}</td>
                                                <td>
                                                    <form action="{{ route('admin.update.backup.delete', $b['name']) }}"
                                                        method="POST" class="delete-backup-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            data-backup="{{ $b['name'] }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">No backups found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.querySelector("form[action='{{ route('admin.update.run') }}']");

            const btn = document.getElementById("updateBtn");
            const icon = document.getElementById("updateIcon");
            const text = document.getElementById("updateText");

            if (!form) return;

            // submit listener, not click
            form.addEventListener("submit", function() {

                // animation start
                icon.classList.add("spin");
                icon.classList.remove("bi-arrow-repeat");
                icon.classList.add("bi-arrow-clockwise");

                // text update
                text.innerText = "Updating...";

                // disable button AFTER submit allowed
                btn.disabled = true;
                btn.classList.add("opacity-75");
            });

        });


        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.delete-backup-form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const backupName = this.querySelector('button[type="submit"]').dataset.backup;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to delete backup "${backupName}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
