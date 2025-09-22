@extends('layouts.front')

@section('title', 'About Us Two')

@section('content')
    <div class="about-style-two-area default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-xl-6 offset-xl-1 col-lg-6 order-lg-last">
                    <div class="about-style-two-thumb">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/thumb/5.jpg') }}" alt="Image Not Found">
                            <div class="shape-card text-light" style="background-image: url({{ asset('assets/front/img/shape/21.png') }});">
                                <h4>Empower your business with us!</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <h4 class="sub-title">About Us</h4>
                    <h2 class="title">Meet the executives driving our success.</h2>
                    <p>Contrasted dissimilar get joy you instrument out reasonably.</p>
                    <div class="card-style-two mt-40">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/thumb/2.jpg') }}" alt="Image Not Found">
                            <a href="https://www.youtube.com/watch?v=HAnw168huqA" class="popup-youtube video-play-button"><i class="fas fa-play"></i></a>
                        </div>
                        <div class="info">
                            <h2>3.8 X</h2>
                            <h5>Economical growth </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="partner-style-one-area default-padding bg-dark text-light" style="background-image: url({{ asset('assets/front/img/shape/25.png') }});">
        <div class="container">
            <div class="row align-center">
                <div class="col-xl-4">
                    <h2 class="title">Thrusted brands work with us</h2>
                </div>
                <div class="col-xl-8 pl-60 pl-md-15 pl-xs-15 brand-one-contents">
                    <div class="brand-style-one-items">
                        <div class="brand-style-one-carousel swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><div class="brand-one"><img src="{{ asset('assets/front/img/brand/11.png') }}" alt=""></div></div>
                                <div class="swiper-slide"><div class="brand-one"><img src="{{ asset('assets/front/img/brand/22.png') }}" alt=""></div></div>
                                <div class="swiper-slide"><div class="brand-one"><img src="{{ asset('assets/front/img/brand/55.png') }}" alt=""></div></div>
                                <div class="swiper-slide"><div class="brand-one"><img src="{{ asset('assets/front/img/brand/66.png') }}" alt=""></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


