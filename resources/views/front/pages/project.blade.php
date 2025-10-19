@extends('layouts.front')

@section('title', 'Projects')

@push('styles')
<style>
    /* Navbar text color fix for projects page */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover,
    .navbar-nav > li > a:focus {
        color: #667eea !important;
    }

    .navbar.white .navbar-nav > li > a,
    .navbar.navbar-scrolled .navbar-nav > li > a {
        color: #2c3e50 !important;
    }
</style>
@endpush

@section('content')
    <div class="gallery-style-one-area bg-gray default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-9">
                    <div class="site-heading">
                        <h4 class="sub-title">Case Studies</h4>
                        <h2 class="title split-text">Have a view of our amazing projects</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fill">
            <div class="row">
                <div class="gallery-style-one-carousel swiper">
                    <div class="swiper-wrapper">
                        @for ($i = 5; $i <= 8; $i++)
                            <div class="swiper-slide">
                                <div class="gallery-style-one">
                                    <img src="{{ asset('assets/front/img/projects/' . $i . '.jpg') }}" alt="Image Not Found">
                                    <div class="overlay">
                                        <div class="info">
                                            <h4><a href="{{ url('/project-details') }}">Project {{ $i }}</a></h4>
                                            <span>Category</span>
                                            <p>Continued at up to zealously necessary breakfast.</p>
                                        </div>
                                        <a href="{{ url('/project-details') }}">Explore <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


