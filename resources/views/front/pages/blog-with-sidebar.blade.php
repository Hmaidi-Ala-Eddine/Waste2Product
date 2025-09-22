@extends('layouts.front')

@section('title', 'Blog With Sidebar')

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

    <div class="blog-area full-blog default-padding-bottom">
        <div class="container">
            <div class="blog-items">
                <div class="row">
                    <div class="blog-content col-xl-8 col-lg-7 col-md-12 pr-35 pr-md-15 pl-md-15 pr-xs-15 pl-xs-15">
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
                    <div class="sidebar col-xl-4 col-lg-5 col-md-12 mt-md-50 mt-xs-50">
                        <aside>
                            <div class="sidebar-item search">
                                <div class="sidebar-info">
                                    <form>
                                        <input type="text" placeholder="Enter Keyword" class="form-control">
                                        <button type="submit"><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


