@extends('layouts.front')

@section('title', 'Blog Grid Three Column')

@section('content')
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Latest Blog</h1>
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

    <div class="blog-area home-blog blog-grid default-padding-bottom">
        <div class="container">
            <div class="blog-item-box">
                <div class="row">
                    @for ($i = 1; $i <= 9; $i++)
                        <div class="col-xl-4 col-md-6 col-lg-6 mb-50">
                            <div class="home-blog-style-one-item animate" data-animate="fadeInUp" data-delay="{{ ($i % 2) ? '100ms' : '200ms' }}">
                                <div class="home-blog-thumb">
                                    <img src="{{ asset('assets/front/img/blog/' . ($i % 9 + 1) . '.jpg') }}" alt="Image not Found">
                                    <ul class="home-blog-meta">
                                        <li><a href="#">category</a></li>
                                        <li>Oct 18, 2024</li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <div class="info">
                                        <h4 class="blog-title">
                                            <a href="{{ url('/blog-single-with-sidebar') }}">Sample post title</a>
                                        </h4>
                                        <a href="{{ url('/blog-single-with-sidebar') }}" class="btn-read-more">Read More <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection


