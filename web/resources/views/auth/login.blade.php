
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center mb-4">
            <a href="/" style="text-decoration:none; color:#4B2067; font-weight:bold; font-size:2rem; font-family:Georgia,serif;">
                <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle; margin-right:10px;">
                    <rect x="6" y="8" width="36" height="32" rx="8" fill="#FFD6E0" stroke="#4B2067" stroke-width="2"/>
                    <rect x="12" y="14" width="24" height="20" rx="4" fill="#fff" stroke="#4B2067" stroke-width="1.5"/>
                    <ellipse cx="24" cy="24" rx="8" ry="7" fill="#FFD6E0"/>
                    <circle cx="21" cy="23" r="1.5" fill="#4B2067"/>
                    <circle cx="27" cy="23" r="1.5" fill="#4B2067"/>
                    <path d="M21 27c1.5 1.5 4.5 1.5 6 0" stroke="#4B2067" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Tiệm sách Hư vô
            </a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
