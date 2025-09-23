@extends('layouts.front')

@section('title', 'Home v4 - Creative Agency')

@section('content')
    <!-- Start Banner Area -->
    <div class="banner-style-six-area overflow-hidden bg-cover" style="background-image: url({{ asset('assets/front/img/shape/6.jpg') }});">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-11">
                    <div class="banner-style-six-item">
                        <h2 class="split-text">Creative <strong>Agency</strong></h2>
                        <div class="d-flex justify-content-between">
                            <div class="video-card wow fadeInDown" data-wow-delay="500ms">
                                <div class="thumb">
                                    <img src="{{ asset('assets/front/img/thumb/6.jpg') }}" alt="Image Not Found">
                                    <a href="https://www.youtube.com/watch?v=aTC_RNYtEb0" class="popup-youtube video-play-button"><i class="fas fa-play"></i></a>
                                </div>
                                <h4>Watch Intro Video</h4>
                            </div>
                            <div class="card-style-one mt-30 wow fadeInLeft" data-wow-delay="800ms">
                                <p>
                                    Bndulgence diminution so discovered mr apartments. Are off under folly death wrote cause her way spite. Plan upon yet way get cold spot its week.
                                </p>
                                <div class="item-author">
                                    <div class="left">
                                        <h5>Craetive Digital Marketing</h5>
                                        <span>Autin Barber</span>
                                    </div>
                                    <a href="{{ route('front.about2') }}"><i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Parallax -->
    <div class="parallax-area">
        <img src="{{ asset('assets/front/img/shape/27.png') }}" alt="Image Not Found">
        <div class="img-container shape">
            <img src="{{ asset('assets/front/img/banner/20.jpg') }}" alt="Image Not Found">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="circle-progress-style-two text-center">
                        <div class="circle-text-card">
                            <div class="circle-text style-two">
                                <div class="circle-text-item" data-circle-text-options='{"radius": 105, "forceWidth": true, "forceHeight": true }'>
                                    .  Certified Creative   .  Digital Agency Company
                                </div>
                            </div>
                            <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                        </div>
                        <h2 class="title split-text">Best creative & modern agency</h2>
                        <p class="split-text">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ab veniam ullam vero officia incidunt ea, odio excepturi aut ipsum quis nihil eius ipsa at est libero reprehenderit sapiente iure voluptatem?
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start About -->
    <div class="about-style-five-area default-padding mt--70">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-6">
                    <div class="thumb-style-five">
                        <img src="{{ asset('assets/front/img/thumb/7.jpg') }}" alt="Image Not Found">
                    </div>
                </div>
                <div class="col-lg-6 pl-80 pl-md-15 pl-xs-15">
                    <div class="about-style-five-info">
                        <div class="text-scroll-animation">
                            <h2 class="title text">Crafting unique solutions with precision and passion.</h2>
                            <div class="d-flex mt-50">
                                <div class="left">
                                    <div class="achivement-style-one">
                                        <div class="fun-fact">
                                            <div class="counter">
                                                <div class="timer" data-to="28" data-speed="1000">28</div>
                                                <div class="operator">K</div>
                                            </div>
                                            <span class="medium">Completed Projects</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="achivement-style-one">
                                        <div class="fun-fact">
                                            <div class="counter">
                                                <div class="timer" data-to="15" data-speed="1000">15</div>
                                                <div class="operator">K</div>
                                            </div>
                                            <span class="medium">Happy Clients</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
