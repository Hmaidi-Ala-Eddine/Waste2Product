@extends('layouts.front')

@section('title', 'Home')

@section('content')
    <div class="banner-area banner-style-two content-right navigation-custom-large zoom-effect overflow-hidden text-light">
        <div class="banner-fade">
            <div class="swiper-wrapper">
                <div class="swiper-slide banner-style-two">
                    <div class="banner-thumb bg-cover shadow dark" style="background: url({{ asset('assets/front/img/banner/banner1.png') }});"></div>
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-xl-7 offset-xl-5 col-lg-10 offset-lg-1">
                                <div class="content">
                                    <h4>Waste2Product Initiative</h4>
                                    <h2><strong>Transforming Waste</strong> Into Valuable Resources.</h2>
                                    <div class="button">
                                        <a class="btn circle btn-gradient btn-md radius animation" href="{{ url('/login') }}">Start Your Journey</a>
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
                    <div class="banner-thumb bg-cover shadow dark" style="background: url({{ asset('assets/front/img/banner/banner2.png') }});"></div>
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-xl-7 offset-xl-5 col-lg-10 offset-lg-1">
                                <div class="content">
                                    <h4>Circular Economy Solutions</h4>
                                    <h2><strong>Sustainable Solutions</strong> for Environmental Growth</h2>
                                    <div class="button">
                                        <a class="btn circle btn-gradient btn-md radius animation" href="{{ url('/login') }}">Join the Movement</a>
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

    <!-- Moving Services Banner -->
    <div class="moving-services-banner">
        <div class="moving-text-container">
            <div class="moving-text">
                <span class="service-item">
                    <i class="fas fa-recycle"></i>
                    Waste Collection
                </span>
                <span class="service-item">
                    <i class="fas fa-leaf"></i>
                    Commercial Recycling
                </span>
                <span class="service-item">
                    <i class="fas fa-dumpster"></i>
                    Dumpster Rental
                </span>
                <span class="service-item">
                    <i class="fas fa-seedling"></i>
                    Waste Management
                </span>
                <span class="service-item">
                    <i class="fas fa-apple-alt"></i>
                    Organic Waste
                </span>
                <span class="service-item">
                    <i class="fas fa-handshake"></i>
                    Waste Consulting
                </span>
                <span class="service-item">
                    <i class="fas fa-truck"></i>
                    Waste Collection
                </span>
                <span class="service-item">
                    <i class="fas fa-industry"></i>
                    Commercial Recycling
                </span>
                <span class="service-item">
                    <i class="fas fa-trash-restore"></i>
                    Dumpster Rental
                </span>
                <span class="service-item">
                    <i class="fas fa-globe-americas"></i>
                    Environmental Solutions
                </span>
                <!-- Duplicate for continuous scrolling -->
                <span class="service-item">
                    <i class="fas fa-recycle"></i>
                    Waste Collection
                </span>
                <span class="service-item">
                    <i class="fas fa-leaf"></i>
                    Commercial Recycling
                </span>
                <span class="service-item">
                    <i class="fas fa-dumpster"></i>
                    Dumpster Rental
                </span>
                <span class="service-item">
                    <i class="fas fa-seedling"></i>
                    Waste Management
                </span>
                <span class="service-item">
                    <i class="fas fa-apple-alt"></i>
                    Organic Waste
                </span>
                <span class="service-item">
                    <i class="fas fa-handshake"></i>
                    Waste Consulting
                </span>
                <span class="service-item">
                    <i class="fas fa-truck"></i>
                    Waste Collection
                </span>
                <span class="service-item">
                    <i class="fas fa-industry"></i>
                    Commercial Recycling
                </span>
                <span class="service-item">
                    <i class="fas fa-trash-restore"></i>
                    Dumpster Rental
                </span>
                <span class="service-item">
                    <i class="fas fa-globe-americas"></i>
                    Environmental Solutions
                </span>
            </div>
        </div>
    </div>

    <div class="about-style-three-area default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-6">
                    <div class="about-style-three-info">
                        <h4 class="sub-title">About Us</h4>
                        <h2 class="title split-text">Pioneering the circular economy for a sustainable future</h2>
                        <p class="split-text">
                            We are an innovative initiative that transforms waste into valuable products through reuse, repair, and creative transformation. Our mission highlights the importance of circular economy principles by reducing landfill waste and promoting responsible environmental practices for a greener tomorrow.
                        </p>
                        <div class="info-grid mt-50">
                            <div class="left-info wow fadeInLeft" data-wow-delay="200ms">
                                <div class="fun-fact-card-two">
                                    <h4 class="sub-title">Our Impact</h4>
                                    <div class="counter-title">
                                        <div class="counter">
                                            <div class="timer" data-to="26" data-speed="2000">26</div>
                                            <div class="operator">+</div>
                                        </div>
                                    </div>
                                    <span class="medium">Years transforming waste</span>
                                </div>
                            </div>
                            <div class="right-info bg-gradient text-light wow fadeInLeft" data-wow-delay="400ms">
                                <ul class="list-style-three">
                                    <li>Waste transformation</li>
                                    <li>Circular economy solutions</li>
                                    <li>Sustainable product design</li>
                                    <li>Environmental impact consulting</li>
                                    <li>Recycling innovations</li>
                                    <li>Green technology support</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="thumb-style-two wow fadeInUp">
                        <img src="{{ asset('assets/front/img/banner/imgs/13.png') }}" alt="Waste Management Solutions">
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
                        <h4 class="sub-title">Our Services</h4>
                        <h2 class="title split-text">Transforming waste into opportunities for everyone</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two wow fadeInRight">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/banner/imgs/1.png') }}" alt="Waste Collection Service">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('assets/front/img/icon/13.png') }}" alt="Icon Not Found">
                                    <h4><a href="{{ route('front.services.details') }}">Waste Collection</a></h4>
                                </div>
                                <a href="{{ route('front.services.details') }}"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('assets/front/img/icon/13.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="{{ route('front.services.details') }}">Waste Collection</a></h4>
                                    <p>
                                        Efficient waste collection services from homes, businesses, and industrial sites, ensuring proper sorting and responsible handling of all materials.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two wow fadeInRight" data-wow-delay="200ms">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/banner/imgs/2.png') }}" alt="Material Recovery Process">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('assets/front/img/icon/14.png') }}" alt="Icon Not Found">
                                    <h4><a href="{{ route('front.services.details') }}">Material Recovery</a></h4>
                                </div>
                                <a href="{{ route('front.services.details') }}"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('assets/front/img/icon/14.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="{{ route('front.services.details') }}">Material Recovery</a></h4>
                                    <p>
                                        Advanced sorting and processing techniques to recover valuable materials from waste streams, maximizing resource recovery and minimizing environmental impact.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two wow fadeInRight" data-wow-delay="400ms">
                        <div class="thumb">
                            <img src="{{ asset('assets/front/img/banner/imgs/4.png') }}" alt="Product Transformation">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('assets/front/img/icon/15.png') }}" alt="Icon Not Found">
                                    <h4><a href="{{ route('front.services.details') }}">Product Transformation</a></h4>
                                </div>
                                <a href="{{ route('front.services.details') }}"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('assets/front/img/icon/14.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="{{ route('front.services.details') }}">Product Transformation</a></h4>
                                    <p>
                                        Creative transformation of waste materials into new, valuable products through innovative design, repair, and upcycling processes.
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
                    <h2 class="title split-text">Trusted partners in sustainability</h2>
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
                        <h2 class="title split-text">Leading environmental transformation since 1998</h2>
                        <p class="split-text">
                            We are committed to creating a sustainable future through innovative waste transformation solutions. Our expertise in circular economy principles and environmental stewardship makes us the ideal partner for your sustainability journey.
                        </p>
                        <ul class="list-sytle-four mt-30">
                            <li class="wow fadeInUp">
                                <h4>Eco Solutions</h4>
                                <p>Innovative environmental solutions that transform waste into valuable resources while protecting our planet.</p>
                            </li>
                            <li class="wow fadeInUp">
                                <h4>Rapid Response</h4>
                                <p>Fast and efficient waste collection and processing services to meet your environmental goals quickly.</p>
                            </li>
                        </ul>
                        <a class="btn btn-md circle btn-gradient animation mt-20" href="{{ url('/about-us') }}">Learn More</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="thumb-style-three">
                        <img src="{{ asset('assets/front/img/banner/imgs/2 (2).png') }}" alt="Environmental Solutions">
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
                        <h2 class="title split-text">Empower your sustainability with our eco-services.</h2>
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
                            <h4><a href="#">Waste Analysis Solutions</a></h4>
                            <p>
                                Comprehensive waste stream analysis to identify opportunities for reduction, reuse, and recycling. Data-driven insights to optimize your environmental impact and resource efficiency.
                            </p>
                            <div class="d-flex mt-30">
                                <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                                <div class="service-tags">
                                    <a href="#">Analysis</a>
                                    <a href="#">Optimization</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                    <div class="services-style-three-item wow fadeInUp" data-wow-delay="200ms">
                        <div class="item-title">
                            <img src="{{ asset('assets/front/img/icon/17.png') }}" alt="">
                            <h4><a href="#">Environmental Risk Management</a></h4>
                            <p>
                                Proactive environmental risk assessment and management strategies to ensure compliance with regulations and minimize ecological impact throughout your operations.
                            </p>
                            <div class="d-flex mt-30">
                                <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                                <div class="service-tags">
                                    <a href="#">Compliance</a>
                                    <a href="#">Assessment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                    <div class="services-style-three-item wow fadeInUp" data-wow-delay="400ms">
                        <div class="item-title">
                            <img src="{{ asset('assets/front/img/icon/18.png') }}" alt="">
                            <h4><a href="#">Advanced Recycling</a></h4>
                            <p>
                                State-of-the-art recycling technologies and processes that maximize material recovery rates and create high-quality recycled products for various industries.
                            </p>
                            <div class="d-flex mt-30">
                                <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                                <div class="service-tags">
                                    <a href="#">Technology</a>
                                    <a href="#">Recovery</a>
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
                    <div class="fun-fact-style-two text-light" style="background-image: url({{ asset('assets/front/img/banner/imgs/3 (2) (1).png') }});">
                        <div class="fun-fact">
                            <div class="counter-title">
                                <div class="counter">
                                    <div class="timer" data-to="98" data-speed="2000">98</div>
                                    <div class="operator">%</div>
                                </div>
                            </div>
                            <span class="medium">Successful Transformations</span>
                        </div>
                        <div class="fun-fact">
                            <div class="counter-title">
                                <div class="counter">
                                    <div class="timer" data-to="38" data-speed="2000">38</div>
                                    <div class="operator">K</div>
                                </div>
                            </div>
                            <span class="medium">Eco Partners</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 offset-xl-1 col-lg-8">
                    <div class="speciality-items">
                        <h4 class="sub-title">Our Expertise</h4>
                        <h2 class="title">Our commitment <br> is environmental excellence</h2>
                        <div class="d-grid mt-40">
                            <ul class="list-style-two">
                                <li>Circular economy implementation</li>
                                <li>Environmental impact guarantee</li>
                                <li>Sustainable delivery solutions</li>
                            </ul>
                            <div class="progress-items">
                                <div class="progress-box">
                                    <h5>Waste Management</h5>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" data-width="70">
                                            <span>70%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-box">
                                    <h5>Environmental Safety</h5>
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
                        <h2 class="title split-text">Discover our transformative waste-to-product success stories</h2>
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
                                <img src="{{ asset('assets/front/img/banner/imgs/1(1).png') }}" alt="Plastic Upcycling">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">Plastic Upcycling Initiative</a></h4>
                                        <span>Recycling, Innovation</span>
                                        <p>
                                            Transforming plastic waste into durable construction materials, reducing landfill burden while creating valuable building resources.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/banner/imgs/2 (1).png') }}" alt="Organic Waste Composting">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">Organic Waste Composting</a></h4>
                                        <span>Composting, Agriculture</span>
                                        <p>
                                            Converting organic waste into nutrient-rich compost, supporting sustainable agriculture and reducing methane emissions.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/banner/imgs/3 (1).png') }}" alt="E-Waste Recovery">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">E-Waste Recovery Program</a></h4>
                                        <span>Electronics, Recovery</span>
                                        <p>
                                            Extracting valuable metals and components from electronic waste, preventing toxic materials from harming the environment.
                                        </p>
                                    </div>
                                    <a href="#">Explore <i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-style-one">
                                <img src="{{ asset('assets/front/img/banner/imgs/4 (2).png') }}" alt="Textile Regeneration">
                                <div class="overlay">
                                    <div class="info">
                                        <h4><a href="{{ route('front.project.details') }}">Textile Regeneration Project</a></h4>
                                        <span>Textiles, Circular Economy</span>
                                        <p>
                                            Transforming discarded textiles into new fabrics and products, extending material lifecycles and reducing fashion waste.
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

    <!-- Second Moving Services Banner -->
    <div class="moving-services-banner">
        <div class="moving-text-container">
            <div class="moving-text">
                <span class="service-item">
                    <i class="fas fa-leaf"></i>
                    Eco-Friendly Solutions
                </span>
                <span class="service-item">
                    <i class="fas fa-recycle"></i>
                    Circular Economy
                </span>
                <span class="service-item">
                    <i class="fas fa-globe-americas"></i>
                    Environmental Impact
                </span>
                <span class="service-item">
                    <i class="fas fa-seedling"></i>
                    Sustainable Future
                </span>
                <span class="service-item">
                    <i class="fas fa-hands-helping"></i>
                    Community Partnership
                </span>
                <span class="service-item">
                    <i class="fas fa-lightbulb"></i>
                    Innovation Hub
                </span>
                <span class="service-item">
                    <i class="fas fa-heart"></i>
                    Planet Care
                </span>
                <span class="service-item">
                    <i class="fas fa-tree"></i>
                    Green Technology
                </span>
                <span class="service-item">
                    <i class="fas fa-water"></i>
                    Clean Environment
                </span>
                <span class="service-item">
                    <i class="fas fa-sun"></i>
                    Renewable Energy
                </span>
                <!-- Duplicate for continuous scrolling -->
                <span class="service-item">
                    <i class="fas fa-leaf"></i>
                    Eco-Friendly Solutions
                </span>
                <span class="service-item">
                    <i class="fas fa-recycle"></i>
                    Circular Economy
                </span>
                <span class="service-item">
                    <i class="fas fa-globe-americas"></i>
                    Environmental Impact
                </span>
                <span class="service-item">
                    <i class="fas fa-seedling"></i>
                    Sustainable Future
                </span>
                <span class="service-item">
                    <i class="fas fa-hands-helping"></i>
                    Community Partnership
                </span>
                <span class="service-item">
                    <i class="fas fa-lightbulb"></i>
                    Innovation Hub
                </span>
                <span class="service-item">
                    <i class="fas fa-heart"></i>
                    Planet Care
                </span>
                <span class="service-item">
                    <i class="fas fa-tree"></i>
                    Green Technology
                </span>
                <span class="service-item">
                    <i class="fas fa-water"></i>
                    Clean Environment
                </span>
                <span class="service-item">
                    <i class="fas fa-sun"></i>
                    Renewable Energy
                </span>
            </div>
        </div>
    </div>

    <div class="team-style-two-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Team Members</h4>
                        <h2 class="title split-text">Meet our passionate environmental transformation experts</h2>
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
                            <span>Environmental Director</span>
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
                            <span>Sustainability Manager</span>
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
                            <span>Circular Economy Specialist</span>
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
                        <h2 class="split-text">Over 50K eco-partners and 5,000 transformation projects worldwide.</h2>
                        <div class="review-card">
                            <h6>Excellent 18,560+ Environmental Impact Reviews</h6>
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
                                                "Their waste transformation solutions exceeded our expectations. The circular economy approach not only reduced our environmental footprint but also created new revenue streams from our waste materials.
                                            </p>
                                        </div>
                                        <div class="content">
                                            <div class="thumb">
                                                <img src="{{ asset('assets/front/img/team/v1.jpg') }}" alt="">
                                            </div>
                                            <div class="info">
                                                <h4>Matthew J. Wyman</h4>
                                                <span>Sustainability Director</span>
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
                                                "The team's expertise in converting our organic waste into valuable compost has revolutionized our agricultural operations. Truly innovative environmental solutions.
                                            </p>
                                        </div>
                                        <div class="content">
                                            <div class="thumb">
                                                <img src="{{ asset('assets/front/img/team/v2.jpg') }}" alt="">
                                            </div>
                                            <div class="info">
                                                <h4>Anthom Bu Spar</h4>
                                                <span>Green Operations Manager</span>
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
                        <h4 class="sub-title">Sustainability Insights</h4>
                        <h2 class="title split-text">Valuable insights to transform your environmental impact</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-lg-6 mb-30">
                    <div class="home-blog-style-one-item wow fadeInUp">
                        <div class="home-blog-thumb">
                            <img src="{{ asset('assets/front/img/banner/imgs/4 (3).png') }}" alt="Waste-to-Energy Solutions">
                            <ul class="home-blog-meta">
                                <li><a href="#">sustainability</a></li>
                                <li>October 18, 2024</li>
                            </ul>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h2 class="blog-title">
                                    <a href="{{ route('front.blog.single_with_sidebar') }}">Revolutionary Waste-to-Energy Solutions for Modern Cities</a>
                                </h2>
                                <a href="{{ route('front.blog.single_with_sidebar') }}" class="btn-read-more">Read More <i class="fas fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-lg-6 mb-30">
                    <div class="home-blog-style-one-item wow fadeInUp" data-wow-delay="200ms">
                        <div class="home-blog-thumb">
                            <img src="{{ asset('assets/front/img/banner/imgs/3 (2) (1).png') }}" alt="Circular Economy Business Models">
                            <ul class="home-blog-meta">
                                <li><a href="#">circular economy</a></li>
                                <li>August 26, 2024</li>
                            </ul>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h2 class="blog-title">
                                    <a href="{{ route('front.blog.single_with_sidebar') }}">Circular Economy: Transforming Business Models for Sustainability</a>
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
