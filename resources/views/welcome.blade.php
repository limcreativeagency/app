@extends('layouts.app')

@section('content')
<div class="welcome-outer">
    <div class="container">
        <div class="hero-section">
            <div class="hero-content">
                <div class="ai-badge mt-4"><i class="bi bi-robot"></i> {{ __('auth.hero_badge') }}</div>
                <div class="hero-title">{{ __('auth.hero_title') }}</div>
                <div class="hero-desc">
                    {!! str_replace(':brand', '<span class="hero-brand">'.__('auth.hero_brand').'</span>', __('auth.hero_desc')) !!}
                </div>
                <div class="hero-btns mt-5 d-flex flex-wrap align-items-center">
                    <a href="{{ route('register.step1') }}" class="btn btn-success btn-lg"><i class="bi bi-person-plus me-2"></i>{{ __('auth.hero_start_free') }}</a>
                    <a href="#" class="btn btn-outline-primary btn-lg"><i class="bi bi-info-circle me-2"></i>{{ __('auth.hero_how_it_works') }}</a>
                </div>
                <div class="hero-stat-label fw-bold">{{ __('auth.hero_trial') }}</div>
                <div class="hero-stats">
                    <div class="hero-avatars d-inline-flex align-items-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="avatar">
                        <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="avatar">
                    </div>
                    <div>
                        <div class="hero-stat-value">10K+</div>
                        <div class="hero-stat-label">{{ __('auth.hero_active_users') }}</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">4.9/5 <i class="bi bi-star-fill text-warning"></i></div>
                        <div class="hero-stat-label">{{ __('auth.hero_user_score') }}</div>
                    </div>
                </div>
            </div>
            <div class="text-center flex-shrink-0" style="min-width:50%;">
                <img src="{{ asset('images/maskot.png') }}" alt="Robot" class="img-fluid" style="max-height: 440px;">
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
