<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="{{ asset('public/backend/login_css/style.css') }}">

    <style>
        .roleBox {
            display: flex;
            justify-content: space-between;
            margin: 1px 0;
            color: #fff;
            gap: 20px;
        }

        .roleOption {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }

        /* Hide default radio */
        .roleOption input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Custom circle */
        .customRadio {
            position: absolute;
            left: 0;
            top: 0px;
            height: 20px;
            width: 20px;
            border: 2px solid #00FF00;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        /* Add tick when checked */
        .customRadio:after {
            content: "✔";
            position: absolute;
            display: none;
            color: white;
            font-size: 14px;
            left: 2px;
            top: -2px;
        }

        /* Checked effect */
        .roleOption input:checked ~ .customRadio {
            background-color: #00FF00;
            border-color: #00FF00;
        }

        .roleOption input:checked ~ .customRadio:after {
            display: block;
        }


    </style>
</head>
<body>
    <section>

        <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        <span></span>

        <div class="main">
            <div class="content">
                <h2>Sign up</h2>

                <!-- Laravel Register Form -->
                <form class="form" action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="inputBx">
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                        <i>Your Name</i>
                        @error('name')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="inputBx">
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        <i>Email</i>
                        @error('email')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="inputBx">
                        <input type="password" name="password" required>
                        <i>Password</i>
                        @error('password')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="inputBx">
                        <input type="password" name="password_confirmation" required>
                        <i>Confirm Password</i>
                        @error('password_confirmation')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="roleBox">
                        <label class="roleOption">
                            <input type="radio" name="role" value="student" checked>
                            <span class="customRadio"></span>
                            Student
                        </label>

                        <label class="roleOption">
                            <input type="radio" name="role" value="teacher">
                            <span class="customRadio"></span>
                            Teacher
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="inputBx">
                        <button type="submit">Register</button>
                    </div>

                    <!-- Links -->
                    <div class="links">
                        <p style="color: #fff;">Already have an account?</p>
                        <a href="{{ route('login') }}" style="color: #0f0;">Login</a>
                    </div>

                </form>

                <p class="text">&copy; 2025 All Rights Reserved | Niaz </p>
            </div>
        </div>


    </section>
</body>
</html>