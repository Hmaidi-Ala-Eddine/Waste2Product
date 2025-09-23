@extends('layouts.front')

@section('title', 'Home v3 - IT Solutions')

@section('content')
    <!-- Start Banner Area -->
    <div class="banner-area banner-style-two content-right navigation-custom-large zoom-effect overflow-hidden text-light">
        <div class="banner-fade">
            <div class="swiper-wrapper">
                <div class="swiper-slide banner-style-two">
                    <div class="banner-thumb bg-cover shadow dark" style="background: url({{ asset('assets/front/img/banner/1.jpg') }});"></div>
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-xl-7 offset-xl-5 col-lg-10 offset-lg-1">
                                <div class="content">
                                    <h4>Meet Consulting</h4>
                                    <h2><strong>Financial Analysis</strong> Developing Meeting.</h2>
                                    <div class="button">
                                        <a class="btn circle btn-gradient btn-md radius animation" href="{{ route('front.contact') }}">Get Consultant</a>
                                    </div>
                                    <div class="shape-circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-angle-shape">
                        <div class="shape-item" style="background: url({{ asset('assets/front/img/shape/2.png') }});"></div>
                    </div>
                </div>
                <div class="swiper-slide banner-style-two">
                    <div class="banner-thumb bg-cover shadow dark" style="background: url({{ asset('assets/front/img/banner/4.jpg') }});"></div>
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-xl-7 offset-xl-5 col-lg-10 offset-lg-1">
                                <div class="content">
                                    <h4>Coaching & Consulting</h4>
                                    <h2><strong>Proper solution</strong> for business growth</h2>
                                    <div class="button">
                                        <a class="btn circle btn-gradient btn-md radius animation" href="{{ route('front.contact') }}">Get Consultant</a>
                                    </div>
                                    <div class="shape-circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-angle-shape">
                        <div class="shape-item" style="background: url({{ asset('assets/front/img/shape/2.png') }});"></div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Start About -->
    <div class="about-style-three-area default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-6">
                    <div class="about-style-three-info">
                        <h4 class="sub-title">About Us</h4>
                        <h2 class="title split-text">Providing technology for smart it solutions</h2>
                        <p class="split-text">
                            Numerous ladyship so raillery humoured goodness received an. So narrow formal length my highly longer afford oh. Tall neat he make or at dull ye. Lorem ipsum dolor, sit amet consectetur adipisicing, elit. Iure, laudantium, tempore.
                        </p>
                        <div class="info-grid mt-50">
                            <div class="left-info wow fadeInLeft" data-wow-delay="200ms">
                                <div class="fun-fact-card-two">
                                    <h4 class="sub-title">Our Expertise</h4>
                                    <div class="counter-title">
                                        <div class="counter">
                                            <div class="timer" data-to="26" data-speed="2000">26</div>
                                            <div class="operator">+</div>
                                        </div>
                                    </div>
                                    <span class="medium">Years of experience</span>
                                </div>
                            </div>
                            <div class="right-info bg-gradient text-light wow fadeInLeft" data-wow-delay="400ms">
                                <ul class="list-style-three">
                                    <li>Network security</li>
                                    <li>Mobile networking</li>
                                    <li>Cloud computing</li>
                                    <li>Information technology consulting</li>
                                    <li>Backup solutions</li>
                                    <li>Hardware support</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="thumb-style-two wow fadeInUp">
                        <img src="{{ asset('assets/front/img/about/4.jpg') }}" alt="Image Not Found">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Features -->
    <div class="features-style-two-area default-padding bottom-less bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Our Features</h4>
                        <h2 class="title split-text">Our goal is giving the best our customers</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two wow fadeInRight">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/features/1.jpg') }}" alt="Thumb">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('assets/front/img/icon/13.png') }}" alt="Icon Not Found">
                                    <h4><a href="{{ route('front.services.details') }}">Manage it service</a></h4>
                                </div>
                                <a href="{{ route('front.services.details') }}"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('assets/front/img/icon/13.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="{{ route('front.services.details') }}">Manage it service</a></h4>
                                    <p>
                                        Prevailed mr tolerably discourse assurance estimable everything melancholy uncommonly solicitude inhabiting projection.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two wow fadeInRight" data-wow-delay="200ms">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/features/2.jpg') }}" alt="Thumb">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('assets/front/img/icon/14.png') }}" alt="Icon Not Found">
                                    <h4><a href="{{ route('front.services.details') }}">Cyber Security</a></h4>
                                </div>
                                <a href="{{ route('front.services.details') }}"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('assets/front/img/icon/14.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="{{ route('front.services.details') }}">Cyber Security</a></h4>
                                    <p>
                                        Prevailed mr tolerably discourse assurance estimable everything melancholy uncommonly solicitude inhabiting projection.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two wow fadeInRight" data-wow-delay="400ms">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/features/3.jpg') }}" alt="Thumb">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('assets/front/img/icon/15.png') }}" alt="Icon Not Found">
                                    <h4><a href="{{ route('front.services.details') }}">Digital Experience</a></h4>
                                </div>
                                <a href="{{ route('front.services.details') }}"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('assets/front/img/icon/15.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="{{ route('front.services.details') }}">Digital Experience</a></h4>
                                    <p>
                                        Prevailed mr tolerably discourse assurance estimable everything melancholy uncommonly solicitude inhabiting projection.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
