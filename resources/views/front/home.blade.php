@extends('layouts.front')

@section('title', 'Home')

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
                                        <a class="btn circle btn-gradient btn-md radius animation" href="{{ url('/login') }}">Get Consultant</a>
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
                                        <a class="btn circle btn-gradient btn-md radius animation" href="{{ url('/login') }}">Get Consultant</a>
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
                                        <img src="{{ asset('assets/front/img/icon/14.png') }}" alt="Icon Not Found">
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

    <div class="partner-style-one-area default-padding bg-dark text-light" style="background-image: url({{ asset('assets/front/img/shape/25.png') }});">
        <div class="container">
            <div class="row align-center">
                <div class="col-xl-4">
                    <h2 class="title split-text">Thrusted brands work with us</h2>
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

    <div class="choose-us-style-two-area relative bg-dark text-light">
        <div class="container">
            <div class="row align-center">
                <div class="col-xl-6 order-xl-last pl-80 pl-md-15 pl-xs-15 choose-us-style-two-content">
                    <div class="info-style-one">
                        <h4 class="sub-title">Why Choose Us</h4>
                        <h2 class="title split-text">Empowering success in technology since 1968 </h2>
                        <p class="split-text">
                            Continue indulged speaking the was out horrible for domestic position. Seeing rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves.
                        </p>
                        <ul class="list-sytle-four mt-30">
                            <li class="wow fadeInUp">
                                <h4>Tech Solution</h4>
                                <p>Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.</p>
                            </li>
                            <li class="wow fadeInUp">
                                <h4>Quick support</h4>
                                <p>Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.</p>
                            </li>
                        </ul>
                        <a class="btn btn-md circle btn-gradient animation mt-20" href="{{ url('/about-us') }}">Learn More</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="thumb-style-three">
                        <img src="{{ asset('assets/front/img/illustration/7.png') }}" alt="Illustration">
                        <div class="circle-text" style="background-image: url({{ asset('assets/front/img/shape/26.png') }});">
                            <div class="circle-text-item" data-circle-text-options='{"radius": 81, "forceWidth": true, "forceHeight": true }'>
                                .  Certified Company   .  IT Consulting Solution
                            </div>
                            <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="services" class="services-style-three-area default-padding bottom-less bg-gray-secondary bg-cover" style="background-image: url({{ asset('assets/front/img/shape/24.png') }});">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Our Services</h4>
                        <h2 class="title split-text">Empower your business with our services.</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                    <div class="services-style-three-item wow fadeInUp">
                        <div class="item-title">
                            <img src="{{ asset('assets/front/img/icon/16.png') }}" alt="">
                            <h4><a href="#">Analytic Solutions</a></h4>
                            <p>
                                Seeing rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves the perfect connections.
                            </p>
                            <div class="d-flex mt-30">
                                <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                                <div class="service-tags">
                                    <a href="#">Management</a>
                                    <a href="#">Backup</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                    <div class="services-style-three-item wow fadeInUp" data-wow-delay="200ms">
                        <div class="item-title">
                            <img src="{{ asset('assets/front/img/icon/17.png') }}" alt="">
                            <h4><a href="#">Risk Management</a></h4>
                            <p>
                                Regular rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves the perfect connections.
                            </p>
                            <div class="d-flex mt-30">
                                <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                                <div class="service-tags">
                                    <a href="#">Hardware</a>
                                    <a href="#">Error</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                    <div class="services-style-three-item wow fadeInUp" data-wow-delay="400ms">
                        <div class="item-title">
                            <img src="{{ asset('assets/front/img/icon/18.png') }}" alt="">
                            <h4><a href="#">Firewall Advance</a></h4>
                            <p>
                                Patient rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves the perfect connections.
                            </p>
                            <div class="d-flex mt-30">
                                <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                                <div class="service-tags">
                                    <a href="#">Network</a>
                                    <a href="#">Firewall</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="speciality-style-one-area default-padding-bottom">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-4">
                    <div class="fun-fact-style-two text-light" style="background-image: url({{ asset('assets/front/img/shape/1.jpg') }});">
                        <div class="fun-fact">
                            <div class="counter-title">
                                <div class="counter">
                                    <div class="timer" data-to="98" data-speed="2000">98</div>
                                    <div class="operator">%</div>
                                </div>
                            </div>
                            <span class="medium">Successfull Projects</span>
                        </div>
                        <div class="fun-fact">
                            <div class="counter-title">
                                <div class="counter">
                                    <div class="timer" data-to="38" data-speed="2000">38</div>
                                    <div class="operator">K</div>
                                </div>
                            </div>
                            <span class="medium">Happy Clients</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 offset-xl-1 col-lg-8">
                    <div class="speciality-items">
                        <h4 class="sub-title">Our expertise</h4>
                        <h2 class="title">Our commitment <br> is client satisfaction </h2>
                        <div class="d-grid mt-40">
                            <ul class="list-style-two">
                                <li>Organizational structure model </li>
                                <li>Satisfaction guarantee</li>
                                <li>Ontime delivery</li>
                            </ul>
                            <div class="progress-items">
                                <div class="progress-box">
                                    <h5>IT Managment</h5>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" data-width="70">
                                            <span>70%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-box">
                                    <h5>Data Security</h5>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" data-width="95">
                                            <span>95%</span>
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

    <div class="gallery-style-one-area bg-gray default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-9">
                    <div class="site-heading">
                        <h4 class="sub-title">Case Studies</h4>
                        <h2 class="title split-text">Have a view of our amazing projects with our clients</h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-3">
                    <div class="project-navigation-items">
                        <div class="project-swiper-nav">
                            <div class="project-pagination"></div>
                            <div class="project-button-prev"></div>
                            <div class="project-button-next"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fill">
            <div class="row">
                <div class="gallery-style-one-carousel swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/projects/5.jpg') }}" alt="">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">Cyber Security</a></h4>
                                        <span>Technology, IT</span>
                                        <p>
                                            Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/projects/6.jpg') }}" alt="">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">IT Counsultancy</a></h4>
                                        <span>Security, Firewall</span>
                                        <p>
                                            Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/projects/7.jpg') }}" alt="">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">Analysis of Security</a></h4>
                                        <span>Support, Tech</span>
                                        <p>
                                            Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/projects/8.jpg') }}" alt="">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">Business Analysis</a></h4>
                                        <span>Network, Error</span>
                                        <p>
                                            Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="team-style-two-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Team Members</h4>
                        <h2 class="title split-text">Meet the talented team form our company</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp">
                    <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/team/v4.jpg') }}" alt="">
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                        <div class="info">
                            <h4><a href="{{ route('front.team.details') }}">Aleesha Brown</a></h4>
                            <span>CEO & Founder</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp" data-wow-delay="200ms">
                    <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/team/v5.jpg') }}" alt="">
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                        <div class="info">
                            <h4><a href="{{ route('front.team.details') }}">Kevin Martin</a></h4>
                            <span>Product Manager</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp" data-wow-delay="400ms">
                    <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/team/v1.jpg') }}" alt="">
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                        <div class="info">
                            <h4><a href="{{ route('front.team.details') }}">Sarah Albert</a></h4>
                            <span>Financial Consultant</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="testimonial-style-two-area bg-dark default-padding text-light bg-cover" style="background-image: url({{ asset('assets/front/img/shape/5.jpg') }});">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="testimonial-two-info">
                        <div class="icon">
                            <img src="{{ asset('assets/front/img/quote.png') }}" alt="">
                        </div>
                        <h2 class="split-text">Over 50K clients and 5,000 projects across the globe.</h2>
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
                </div>
                <div class="col-lg-8 pl-60 pl-md-15 pl-xs-15">
                    <div class="testimonial-style-two-carousel swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="item">
                                        <div class="text-info">
                                            <p>
                                                "Targeting consultation apartments. ndulgence creative under folly death wrote cause her way spite. Plan upon yet way get cold spot its week.
                                            </p>
                                        </div>
                                        <div class="content">
                                            <div class="thumb">
                                                <img src="{{ asset('assets/front/img/team/v1.jpg') }}" alt="">
                                            </div>
                                            <div class="info">
                                                <h4>Matthew J. Wyman</h4>
                                                <span>Senior Consultant</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="item">
                                        <div class="text-info">
                                            <p>
                                                "Consultation discover apartments. ndulgence off under folly death wrote cause her way spite. Plan upon yet way get cold spot its week.
                                            </p>
                                        </div>
                                        <div class="content">
                                            <div class="thumb">
                                                <img src="{{ asset('assets/front/img/team/v2.jpg') }}" alt="">
                                            </div>
                                            <div class="info">
                                                <h4>Anthom Bu Spar</h4>
                                                <span>Marketing Manager</span>
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
    </div>

    <div id="blog" class="blog-area home-blog blog-2-col default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Blog Insight</h4>
                        <h2 class="title split-text">Valuable insights to change your startup idea</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-lg-6 mb-30">
                    <div class="home-blog-style-one-item wow fadeInUp">
                        <div class="home-blog-thumb">
                            <img src="{{ asset('assets/front/img/blog/2.jpg') }}" alt="">
                            <ul class="home-blog-meta">
                                <li><a href="#">loan</a></li>
                                <li>October 18, 2024</li>
                            </ul>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h2 class="blog-title">
                                    <a href="{{ route('front.blog.single_with_sidebar') }}">This prefabrice passive house is memorable highly sustainable</a>
                                </h2>
                                <a href="{{ route('front.blog.single_with_sidebar') }}" class="btn-read-more">Read More <i class="fas fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-lg-6 mb-30">
                    <div class="home-blog-style-one-item wow fadeInUp" data-wow-delay="200ms">
                        <div class="home-blog-thumb">
                            <img src="{{ asset('assets/front/img/blog/3.jpg') }}" alt="">
                            <ul class="home-blog-meta">
                                <li><a href="#">insentive</a></li>
                                <li>August 26, 2024</li>
                            </ul>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h2 class="blog-title">
                                    <a href="{{ route('front.blog.single_with_sidebar') }}">Announcing if attachment resolution performing the regular sentim.</a>
                                </h2>
                                <a href="{{ route('front.blog.single_with_sidebar') }}" class="btn-read-more">Read More <i class="fas fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
