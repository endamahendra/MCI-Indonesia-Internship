<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-lg-4 bg-white p-4 rounded shadow">
            <div class="text-center mb-4">
                <a href="https://mci-world.com/" class="logo">
                    <img src="https://mci-world.com/asset/mci-logo.png" alt="MCI World">
                </a>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" placeholder="John.Doe@example.com" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="********" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block">Login</button>
                </div>
                {{-- <div class="form-group">
                    <a href="/auth/google" class="btn btn-primary btn-block">
                        <i class="fab fa-google mr-2"></i>
                        Login dengan Google
                    </a>
                </div> --}}
            </form>
        </div>
    </div>

    <!-- Bootstrap JS, jika diperlukan -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
