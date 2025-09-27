@extends('layouts.front')

@section('title', 'About Us')

@section('content')
    <div class="about-style-one-area shape-less default-padding">
        <div class="container">
            <div class="about-style-one-items">
                <div class="row">
                    <div class="col-xl-7 col-lg-6">
                        <div class="thumb-style-one">
                            <img src="{{ asset('assets/front/img/about/1.jpg') }}" alt="Image Not Found">
                            <a href="{{ config('site.intro_video_url') }}" class="popup-youtube video-play-button"><i class="fas fa-play"></i></a>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 pl-50 pl-md-15 pl-xs-15">
                        <div class="about-style-one-info">
                            <div class="content">
                                <h2 class="title">Meet the executives driving our success.</h2>
                                <p>
                                    Businesses operate in various industries, including technology, finance, healthcare, retail, and manufacturing, among others.
                                </p>
                            </div>
                            <ul class="card-list">
                                <li>
                                    <img src="{{ asset('assets/front/img/icon/4.png') }}" alt="Image Not Found">
                                    <h5>Award Winning Company</h5>
                                </li>
                                <li>
                                    <h2>3.8 X</h2>
                                    <h5>Economical growth </h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="choose-us-style-one-area overflow-hidden default-padding-top bg-gray">
        <div class="container">
            <div class="heading-left">
                <div class="row">
                    <div class="col-lg-5 offset-lg-1">
                        <div class="experience-style-one">
                            <h2><strong>26</strong> Years of Experience</h2>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <div class="circle-progress">
                            <div class="progressbar">
                                <div class="circle" data-percent="65">
                                    <strong></strong>
                                </div>
                                <h4>Business Development</h4>
                            </div>
                            <div class="progressbar">
                                <div class="circle" data-percent="84">
                                    <strong></strong>
                                </div>
                                <h4>Investment Analysis</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container container-stage">
            <div class="choose-us-one-thumb">
                <div class="content">
                    <div class="left-info">
                        <h2 class="title">Building great future Together, Be with us </h2>
                    </div>
                    <div class="process-style-one">
                        <div class="process-style-one-item">
                            <span>01</span>
                            <h4>Information Collection</h4>
                            <p>Excuse Deal say over contain performance from comparison new melancholy themselves.</p>
                        </div>
                        <div class="process-style-one-item">
                            <span>02</span>
                            <h4>Projection Report Analysis</h4>
                            <p>Excuse Deal say over contain performance from comparison new melancholy themselves.</p>
                        </div>
                        <div class="process-style-one-item">
                            <span>03</span>
                            <h4>Consultation Solution</h4>
                            <p>Excuse Deal say over contain performance from comparison new melancholy themselves.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="team-style-two-area default-padding bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Team Members</h4>
                        <h2 class="title">Meet the talented team form our company</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 team-style-two">
                    <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/team/v4.jpg') }}" alt="Image Not Found">
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                        <div class="info">
                            <h4><a href="#">Aleesha Brown</a></h4>
                            <span>CEO & Founder</span>
                        </div>
                    </div>
                 </div>
                <div class="col-lg-4 col-md-6 team-style-two">
                    <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/team/v5.jpg') }}" alt="Image Not Found">
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                        <div class="info">
                            <h4><a href="#">Kevin Martin</a></h4>
                            <span>Product Manager</span>
                        </div>
                    </div>
                 </div>
                <div class="col-lg-4 col-md-6 team-style-two">
                    <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/team/v1.jpg') }}" alt="Image Not Found">
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                        <div class="info">
                            <h4><a href="#">Sarah Albert</a></h4>
                            <span>Financial Consultant</span>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
@endsection


