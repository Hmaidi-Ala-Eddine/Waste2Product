@extends('layouts.front')

@section('title', 'Blog Standard')

@section('content')
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Blog Standard</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ route('front.home') }}"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">Blog</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-area full-blog blog-standard default-padding-bottom">
        <div class="container">
            <div class="row">
                <div class="blog-content col-xl-10 offset-xl-1 col-md-12">
                    <div class="blog-item-box">
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="item">
                                <div class="thumb">
                                    <a href="{{ url('/blog-single-with-sidebar') }}"><img src="{{ asset('assets/front/img/blog/v' . $i . '.jpg') }}" alt="Thumb"></a>
                                </div>
                                <div class="info">
                                    <div class="meta">
                                        <ul>
                                            <li><a href="#"><i class="far fa-calendar-alt"></i> 12 Aug, 2024</a></li>
                                            <li><a href="#"><i class="far fa-user-circle"></i> Admin</a></li>
                                        </ul>
                                    </div>
                                    <h2 class="title">
                                        <a href="{{ url('/blog-single-with-sidebar') }}">Sample blog title {{ $i }}</a>
                                    </h2>
                                    <p>Brief intro paragraph for post {{ $i }}.</p>
                                    <a class="btn mt-10 btn-md circle btn-theme animation" href="{{ url('/blog-single-with-sidebar') }}">Continue Reading</a>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


