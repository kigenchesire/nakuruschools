<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · {{ $site->schoolName() }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ $site->faviconUrl() ?: asset('images/favicon.svg') }}">
    @vite(['resources/scss/admin.scss', 'resources/js/admin.js'])
</head>
<body>
    <div class="auth-shell">
        <aside class="auth-aside">
            <div class="d-flex align-items-center gap-3 text-white fw-bold fs-5">
                @if ($site->logoUrl())
                    <img src="{{ $site->logoUrl() }}" alt="" style="height:52px">
                @else
                    <x-frontend.logo-mark style="height:52px" />
                @endif
                {{ $site->schoolName() }}
            </div>
            <div>
                <h1 class="mb-3">Manage your school website with ease.</h1>
                <p class="mb-0" style="max-width: 30rem">Update news, photos, documents and school information in one place — changes appear on the website instantly.</p>
            </div>
            <div class="small" style="color: rgba(255,255,255,.55)">&copy; {{ date('Y') }} {{ $site->schoolName() }}</div>
        </aside>

        <div class="auth-form-wrap">
            <main class="auth-form">
                <div class="d-lg-none text-center mb-4">
                    @if ($site->logoUrl())
                        <img src="{{ $site->logoUrl() }}" alt="{{ $site->schoolName() }}" style="height:64px">
                    @else
                        <x-frontend.logo-mark style="height:64px" />
                    @endif
                </div>
                <h2 class="h3 mb-1">Welcome back</h2>
                <p class="text-body-secondary mb-4">Sign in to the website administration panel.</p>

                @if (session('status'))
                    <div class="alert alert-success small" role="status">{{ session('status') }}</div>
                @endif

                <form method="post" action="{{ route('login') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus autocomplete="username">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                            <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="current-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                        <label class="form-check-label" for="remember">Keep me signed in</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">Sign in <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></button>
                </form>

                <p class="small text-body-secondary text-center mt-4 mb-0">
                    Forgot your password? Ask another administrator to reset it for you.<br>
                    <a href="{{ route('home') }}" class="d-inline-block mt-2"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to website</a>
                </p>
            </main>
        </div>
    </div>
</body>
</html>
