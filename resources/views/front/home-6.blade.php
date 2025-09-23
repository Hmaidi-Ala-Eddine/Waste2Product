@extends('layouts.front')

@section('title', 'Home v6 - Financial Advisor')

@section('content')
    <!-- Start Banner Area -->
    <div class="banner-style-three-area bg-cover" style="background-image: url({{ asset('assets/front/img/shape/5.png') }});">
        <div class="banner-shape-right-top"></div>
        <div class="banner-style-three">
            <div class="container">
                <div class="content">
                    <div class="row align-center">
                        <div class="col-xl-6 col-lg-7 pr-50 pr-md-15 pr-xs-15">
                            <div class="information">
                                <h4 class="wow fadeInUp" data-wow-duration="400ms">Business Advisor</h4>
                                <h2 class="wow fadeInUp" data-wow-delay="500ms" data-wow-duration="400ms">
                                    Grow business <br>with grat <span class="relative">advice</span>
                                </h2>
                                <p class="wow fadeInUp" data-wow-delay="900ms" data-wow-duration="400ms">
                                    Dissuade ecstatic and properly saw entirely sir why laughter endeavor. In on my jointure horrible margaret suitable he followed speedily.
                                </p>
                                <div class="button mt-40 wow fadeInUp" data-wow-delay="1200ms" data-wow-duration="400ms">
                                    <a class="btn btn-md circle btn-theme animation" href="{{ route('front.contact') }}">Get Started</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-5 pl-60 pl-md-15 pl-xs-15">
                            <div class="thumb">
                                <img src="{{ asset('assets/front/img/thumb/2.jpg') }}" alt="Thumb">
                                <div class="grow-graph wow fadeInRight">
                                    <img src="{{ asset('assets/front/img/shape/7.png') }}" alt="Image Not Found">
                                    <h5 class="wow fadeInUp" data-wow-delay="300ms">Profit $23,600</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Services -->
    <div class="services-style-two-area default-padding-top bg-gray">
        <div class="services-style-two-thumb">
            <img src="{{ asset('assets/front/img/about/2.jpg') }}" alt="Image Not Found">
            <img src="{{ asset('assets/front/img/shape/20.png') }}" alt="Image Not Found">
        </div>
        <div class="shape">
            <img src="{{ asset('assets/front/img/shape/18.png') }}" alt="Image Not Found">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="review-card">
                        <h6>Excellent 18,560+ Reviews</h6>
                        <div class="d-flex">
                            <div class="icon">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span>4.8/5</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-6">
                    <h4 class="sub-title">Our Services</h4>
                    <h2 class="title split-text">Empower your business with our services.</h2>
                    <ul class="list-style-two mt-20 split-text">
                        <li>Organizational structure model </li>
                        <li>Satisfaction guarantee</li>
                        <li>Ontime delivery</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
