<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Management</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .btn-custom {
      border-radius: 50px;
      padding: 10px 30px;
      font-size: 18px;
      transition: all 0.3s ease-in-out;
    }
    .btn-custom:hover {
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <div class="card p-5 text-center bg-light">
    <h4 class="mb-3 text-primary fw-bold">✨ Welcome to Our</h4>
    <h1 class="display-5 fw-bolder text-dark">
        Student <span class="text-primary">Management</span> System
    </h1>
    <p class="lead text-muted">
        Manage students easily with a modern, fast and responsive system 🚀
    </p>

    @if (auth()->user())
        <div class="d-flex justify-content-center gap-3">
            @if (auth()->check() && auth()->user()->status == 1)
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-custom">Dashboard</a>
            @else
                <a href="{{ route('inactive.dashboard') }}" class="btn btn-primary btn-custom">Dashboard</a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-custom">Logout</button>
            </form>
        </div>

    @elseif (!auth()->user())
        <p class="mb-4 text-muted">Please login or register to continue</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-custom">Login</a>
            <a href="{{ route('register') }}" class="btn btn-success btn-custom">Register</a>
        </div>
    @endif
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
