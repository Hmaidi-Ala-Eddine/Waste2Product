@extends('layouts.front')

@section('title', 'Blog Single With Sidebar')

@section('content')
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Partiality indulgence dispatched to of celebrated.</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ route('front.home') }}"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">Blog Single</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-area single full-blog right-sidebar full-blog default-padding-bottom">
        <div class="container">
            <div class="blog-items">
                <div class="row">
                    <div class="blog-content col-xl-8 col-lg-7 col-md-12 pr-35 pr-md-15 pl-md-15 pr-xs-15 pl-xs-15">
                        <div class="blog-style-two item">
                            <div class="blog-item-box">
                                <div class="thumb">
                                    <a href="#"><img src="{{ asset('assets/front/img/blog/v1.jpg') }}" alt="Thumb"></a>
                                </div>
                                <div class="info">
                                    <div class="meta">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-calendar-alt"></i> March 16, 2022</a></li>
                                            <li><a href="#"><i class="fas fa-user-circle"></i> Admin</a></li>
                                        </ul>
                                    </div>
                                    <p>Give lady of they such they sure it. Me contained explained my education...</p>
                                </div>
                            </div>
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


