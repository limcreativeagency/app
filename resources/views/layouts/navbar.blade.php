<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-bottom-4 py-2 sticky-top modern-navbar" style="z-index: 1030; min-height:64px;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" style="color:#2563eb; font-family:'Fredoka', 'Nunito', Arial, sans-serif; letter-spacing:0.01em;" href="{{ url('/') }}">{{ __('auth.brand') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2 modern-menu">
                @if (Request::is('/') || Request::routeIs('welcome'))
                    <li class="nav-item"><a class="nav-link fw-semibold px-2 active" href="#">{{ __('auth.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.how_it_works') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.features') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.contact') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.about') }}</a></li>
                @elseif(Auth::check())
                    @if(Auth::user()->role_id == 1)
                        <li class="nav-item"><a class="nav-link fw-semibold px-2 active" href="#">Süper Admin Paneli</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">Kullanıcılar</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">Klinikler</a></li>
                    @elseif(Auth::user()->role_id == 2)
                        <li class="nav-item"><a class="nav-link fw-semibold px-2 active" href="{{ route('clinic.dashboard') }}">Klinik Paneli</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold px-2" href="{{ route('users.index.doctor') }}">Doktorlar</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold px-2" href="{{ route('users.index.representative') }}">Temsilciler</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">Hastalar</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">Raporlar</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link fw-semibold px-2 active" href="#">Panel</a></li>
                    @endif
                @else
                    <li class="nav-item"><a class="nav-link fw-semibold px-2 active" href="#">{{ __('auth.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.how_it_works') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.features') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.contact') }}</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-2" href="#">{{ __('auth.about') }}</a></li>
                @endif
            </ul>
            <div class="d-flex gap-2 align-items-center">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-flex align-items-center dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            @if(Auth::user()->role_id == 1)
                                <li>
                                    <a class="dropdown-item" href="{{ url('/admin') }}">Admin Paneli</a>
                                </li>
                            @elseif(Auth::user()->role_id == 2)
                                <li>
                                    <a class="dropdown-item" href="{{ route('clinic.dashboard') }}">Klinik Paneli</a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ url('/dashboard') }}">Kullanıcı Paneli</a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('general.menu.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-flex align-items-center modern-btn" style="font-size:1rem;"><i class="bi bi-box-arrow-in-right me-1"></i>{{ __('auth.login') }}</a>
                    <a href="{{ route('register.step1') }}" class="btn btn-success rounded-pill px-3 py-1 fw-semibold d-flex align-items-center modern-btn" style="font-size:1rem;"><i class="bi bi-person-plus me-1"></i>{{ __('auth.start_free') }}</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700&family=Nunito:wght@400;700&display=swap" rel="stylesheet"> 