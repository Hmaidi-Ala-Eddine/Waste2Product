@extends('layouts.front')

@section('title', 'Blog Single')

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

    <div class="blog-area single full-blog full-blog default-padding-bottom">
        <div class="container">
            <div class="blog-items">
                <div class="row">
                    <div class="blog-content wow fadeInUp col-lg-10 offset-lg-1 col-md-12">
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
                                    <blockquote>
                                        Celebrated share of first to worse. Weddings and any opinions suitable smallest nay.
                                    </blockquote>
                                    <p>Surrounded to me occasional pianoforte alteration unaffected impossible ye...</p>
                                </div>
                            </div>
                        </div>

                        <div class="post-author">
                            <div class="thumb">
                                <img src="{{ asset('assets/front/img/team/v1.jpg') }}" alt="Thumb">
                            </div>
                            <div class="info">
                                <h4><a href="#">Admin</a></h4>
                                <p>Short author bio here.</p>
                            </div>
                        </div>

                        <div class="post-tags share">
                            <div class="tags">
                                <h4>Tags: </h4>
                                <a href="#">Algorithm</a>
                                <a href="#">Data science</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


