@extends('layouts.front')

@section('title', 'Home v5 - Transport & Logistics')

@section('content')
    <!-- Start Banner Area -->
    <div class="banner-area banner-style-five-area content-right navigation-custom-large zoom-effect overflow-hidden text-light">
        <div class="banner-style-three-carousel">
            <div class="swiper-wrapper">
                <div class="swiper-slide banner-style-five">
                    <div class="banner-thumb bg-cover shadow dark-hard" style="background: url({{ asset('assets/front/img/banner/14.jpg') }});"></div>
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-xl-7 col-lg-9 col-md-10">
                                <div class="content">
                                    <h2>Wordlwide logistic services</h2>
                                    <p>
                                        Dissuade ecstatic and properly saw entirely sir why laughter endeavor. Maximum point on my jointure horrible margaret.
                                    </p>
                                    <div class="button">
                                        <a class="btn btn-theme btn-md animation" href="{{ route('front.contact') }}">Contact Us <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                    <div class="shape-circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fixed-item">
                        <img src="{{ asset('assets/front/img/illustration/1.png') }}" alt="Image not found">
                    </div>
                    <div class="logitic-goods">
                        <img src="{{ asset('assets/front/img/illustration/4.png') }}" alt="Image not found">
                    </div>
                    <div class="banner-fixed-bg" style="background: url({{ asset('assets/front/img/shape/10.png') }});"></div>
                </div>
                <div class="swiper-slide banner-style-five">
                    <div class="banner-thumb bg-cover shadow dark-hard" style="background: url({{ asset('assets/front/img/banner/15.jpg') }});"></div>
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-xl-7 col-lg-9 col-md-10">
                                <div class="content">
                                    <h2>Full sustainable cargo solutions!</h2>
                                    <p>
                                        Dissuade ecstatic and properly saw entirely sir why laughter endeavor. Maximum point on my jointure horrible margaret.
                                    </p>
                                    <div class="button">
                                        <a class="btn btn-theme btn-md animation" href="{{ route('front.contact') }}">Contact Us <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                    <div class="shape-circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fixed-item">
                        <img src="{{ asset('assets/front/img/illustration/2.png') }}" alt="Image not found">
                    </div>
                    <div class="logitic-goods">
                        <img src="{{ asset('assets/front/img/illustration/4.png') }}" alt="Image not found">
                    </div>
                    <div class="banner-fixed-bg" style="background: url({{ asset('assets/front/img/shape/10.png') }});"></div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Start About -->
    <div class="about-style-four-area default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-xl-4 col-lg-6">
                    <div class="thumb-style-four">
                        <img src="{{ asset('assets/front/img/illustration/11.png') }}" alt="Image Not Found">
                        <img src="{{ asset('assets/front/img/illustration/10.png') }}" alt="Image Not Found">
                        <div class="expertise-card">
                            <div class="fun-fact">
                                <div class="counter">
                                    <div class="timer" data-to="56" data-speed="2000">56</div>
                                    <div class="operator">K</div>
                                </div>
                                <span class="medium">Clients around the world</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6 pl-50 pl-md-15 pl-xs-15">
                    <h2 class="title split-text"> We'll keep your items damage free</h2>
                    <p class="split-text">
                        Excellence projecting is devonshire dispatched remarkably on estimating. Side in so life past. Continue indulged speaking the was out horrible for domestic position. Seeing rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves.
                    </p>
                    <ul class="list-style-two mt-20">
                        <li>Intermodal Shipping</li>
                        <li>Container Freight</li>
                        <li>Freeze product Shipping</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
