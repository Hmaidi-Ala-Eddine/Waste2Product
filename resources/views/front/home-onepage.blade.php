@extends('layouts.front')

@section('title', 'Home Onepage')

@section('content')
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
                                        <a class="btn circle btn-gradient btn-md radius animation" href="#contact">Get Consultant</a>
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
                                        <a class="btn circle btn-gradient btn-md radius animation" href="#contact">Get Consultant</a>
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

    @include('front.sections.about')
    @include('front.sections.features')
    @include('front.sections.partners')
    @include('front.sections.choose-us')
    @include('front.sections.services')
    @include('front.sections.speciality')
    @include('front.sections.gallery')
    @include('front.sections.team')
    @include('front.sections.testimonials')
    @include('front.sections.blog')
@endsection
