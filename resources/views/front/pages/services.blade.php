@extends('layouts.front')

@section('title', 'Services')

@section('content')
    <div class="services-style-one-area default-padding" style="background: url({{ asset('assets/front/img/shape/12.png') }});">
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
                <div class="col-lg-12">
                    <div class="services-style-one-items">
                        <div class="services-style-one-item out">
                            <div class="icon"><img src="{{ asset('assets/front/img/icon/5.png') }}" alt=""></div>
                            <div class="content">
                                <h4><a href="{{ route('front.services.details') }}">Advanced Business <br> Intelligence</a></h4>
                                <p>Seeing rather her you not esteem men settle genius excuse. Deal say over you age devonshire Comparison new ham melancholy son themselves instrument out reasonably.</p>
                            </div>
                            <div class="button"><a class="btn" href="{{ route('front.services.details') }}"><i class="fas fa-arrow-right"></i></a></div>
                            <span>01</span>
                        </div>
                        <div class="services-style-one-item">
                            <div class="icon"><img src="{{ asset('assets/front/img/icon/6.png') }}" alt=""></div>
                            <div class="content">
                                <h4><a href="{{ route('front.services.details') }}">Business Research <br> And Development</a></h4>
                                <p>Seeing rather her you not esteem men settle genius excuse. Deal say over you age devonshire Comparison new ham melancholy son themselves instrument out reasonably.</p>
                            </div>
                            <div class="button"><a class="btn" href="{{ route('front.services.details') }}"><i class="fas fa-arrow-right"></i></a></div>
                            <span>02</span>
                        </div>
                        <div class="services-style-one-item">
                            <div class="icon"><img src="{{ asset('assets/front/img/icon/7.png') }}" alt=""></div>
                            <div class="content">
                                <h4><a href="{{ route('front.services.details') }}">Digital Project <br> Management System</a></h4>
                                <p>Seeing rather her you not esteem men settle genius excuse. Deal say over you age devonshire Comparison new ham melancholy son themselves instrument out reasonably.</p>
                            </div>
                            <div class="button"><a class="btn" href="{{ route('front.services.details') }}"><i class="fas fa-arrow-right"></i></a></div>
                            <span>03</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="partner-style-two-area default-padding bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 partner-style-one">
                    <div class="partner-style-one-item bg-theme text-light" style="background-image: url({{ asset('assets/front/img/shape/22.png') }});">
                        <h4 class="sub-title">Our Partners</h4>
                        <h2 class="title">Worked with <br> Largest Brands</h2>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 partner-style-one"><div class="partner-style-one-item wow fadeInLeft"><img src="{{ asset('assets/front/img/brand/11.png') }}" alt=""></div></div>
                <div class="col-lg-3 col-md-6 partner-style-one"><div class="partner-style-one-item wow fadeInLeft" data-wow-delay="200ms"><img src="{{ asset('assets/front/img/brand/22.png') }}" alt=""></div></div>
                <div class="col-lg-3 col-md-6 partner-style-one"><div class="partner-style-one-item wow fadeInLeft" data-wow-delay="300ms"><img src="{{ asset('assets/front/img/brand/33.png') }}" alt=""></div></div>
                <div class="col-lg-3 col-md-6 partner-style-one"><div class="partner-style-one-item wow fadeInLeft" data-wow-delay="400ms"><img src="{{ asset('assets/front/img/brand/44.png') }}" alt=""></div></div>
                <div class="col-lg-3 col-md-6 partner-style-one"><div class="partner-style-one-item wow fadeInLeft" data-wow-delay="500ms"><img src="{{ asset('assets/front/img/brand/55.png') }}" alt=""></div></div>
                <div class="col-lg-3 col-md-6 partner-style-one"><div class="partner-style-one-item wow fadeInLeft" data-wow-delay="600ms"><img src="{{ asset('assets/front/img/brand/66.png') }}" alt=""></div></div>
            </div>
        </div>
    </div>

    <div class="speciality-style-one-area default-padding bg-gray">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-4">
                    <div class="fun-fact-style-two text-light" style="background-image: url({{ asset('assets/front/img/shape/1.jpg') }});">
                        <div class="fun-fact"><div class="counter-title"><div class="counter"><div class="timer" data-to="98" data-speed="2000">98</div><div class="operator">%</div></div></div><span class="medium">Successfull Projects</span></div>
                        <div class="fun-fact"><div class="counter-title"><div class="counter"><div class="timer" data-to="38" data-speed="2000">38</div><div class="operator">K</div></div></div><span class="medium">Happy Clients</span></div>
                    </div>
                </div>
                <div class="col-xl-7 offset-xl-1 col-lg-8">
                    <div class="speciality-items">
                        <h4 class="sub-title">Our expertise</h4>
                        <h2 class="title">Our commitment <br> is client satisfaction </h2>
                        <div class="d-grid mt-40">
                            <ul class="list-style-two"><li>Organizational structure model </li><li>Satisfaction guarantee</li><li>Ontime delivery</li></ul>
                            <div class="progress-items">
                                <div class="progress-box"><h5>IT Managment</h5><div class="progress"><div class="progress-bar" role="progressbar" data-width="70"><span>70%</span></div></div></div>
                                <div class="progress-box"><h5>Data Security</h5><div class="progress"><div class="progress-bar" role="progressbar" data-width="95"><span>95%</span></div></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="team-style-one-area default-padding">
        <div class="container">
            <div class="row"><div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2"><div class="site-heading text-center"><h4 class="sub-title">Team Members</h4><h2 class="title">Meet the talented team form our company</h2></div></div></div>
        </div>
        <div class="container">
            <div class="team-style-one-items">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 mb-50"><div class="team-style-one-item"><div class="thumb"><img src="{{ asset('assets/front/img/team/2.jpg') }}" alt=""><div class="social-overlay"><ul><li><a href="#"><i class="fab fa-linkedin-in"></i></a></li><li><a href="#"><i class="fab fa-dribbble"></i></a></li><li><a href="#"><i class="fab fa-facebook-f"></i></a></li></ul><div class="icon"><i class="fas fa-plus"></i></div></div></div><div class="info"><span>CEO & Founder</span><h4><a href="{{ route('front.team.details') }}">Aleesha Brown</a></h4></div></div></div>
                    <div class="col-xl-3 col-lg-4 mb-50"><div class="team-style-one-item"><div class="thumb"><img src="{{ asset('assets/front/img/team/3.jpg') }}" alt=""><div class="social-overlay"><ul><li><a href="#"><i class="fab fa-linkedin-in"></i></a></li><li><a href="#"><i class="fab fa-dribbble"></i></a></li><li><a href="#"><i class="fab fa-facebook-f"></i></a></li></ul><div class="icon"><i class="fas fa-plus"></i></div></div></div><div class="info"><span>Product Manager</span><h4><a href="{{ route('front.team.details') }}">Kevin Martin</a></h4></div></div></div>
                    <div class="col-xl-3 col-lg-4 mb-50"><div class="team-style-one-item"><div class="thumb"><img src="{{ asset('assets/front/img/team/4.jpg') }}" alt=""><div class="social-overlay"><ul><li><a href="#"><i class="fab fa-linkedin-in"></i></a></li><li><a href="#"><i class="fab fa-dribbble"></i></a></li><li><a href="#"><i class="fab fa-facebook-f"></i></a></li></ul><div class="icon"><i class="fas fa-plus"></i></div></div></div><div class="info"><span>Financial Consultant</span><h4><a href="{{ route('front.team.details') }}">Sarah Albert</a></h4></div></div></div>
                </div>
                <div class="row"><div class="col-xl-9 offset-xl-3 col-lg-12"><div class="team-grid"><div class="row">
                    <div class="col-xl-4 col-lg-4 mb-50"><div class="team-style-one-item"><div class="thumb"><img src="{{ asset('assets/front/img/team/7.jpg') }}" alt=""><div class="social-overlay"><ul><li><a href="#"><i class="fab fa-linkedin-in"></i></a></li><li><a href="#"><i class="fab fa-dribbble"></i></a></li><li><a href="#"><i class="fab fa-facebook-f"></i></a></li></ul><div class="icon"><i class="fas fa-plus"></i></div></div></div><div class="info"><span>Developer</span><h4><a href="{{ route('front.team.details') }}">Amanulla Joey</a></h4></div></div></div>
                    <div class="col-xl-4 col-lg-4 mb-50"><div class="team-style-one-item"><div class="thumb"><img src="{{ asset('assets/front/img/team/6.jpg') }}" alt=""><div class="social-overlay"><ul><li><a href="#"><i class="fab fa-linkedin-in"></i></a></li><li><a href="#"><i class="fab fa-dribbble"></i></a></li><li><a href="#"><i class="fab fa-facebook-f"></i></a></li></ul><div class="icon"><i class="fas fa-plus"></i></div></div></div><div class="info"><span>Co Founder</span><h4><a href="{{ route('front.team.details') }}">Kamal Abraham</a></h4></div></div></div>
                    <div class="col-xl-4 col-lg-4 mb-50"><div class="team-style-one-item"><div class="thumb"><img src="{{ asset('assets/front/img/team/9.jpg') }}" alt=""><div class="social-overlay"><ul><li><a href="#"><i class="fab fa-linkedin-in"></i></a></li><li><a href="#"><i class="fab fa-dribbble"></i></a></li><li><a href="#"><i class="fab fa-facebook-f"></i></a></li></ul><div class="icon"><i class="fas fa-plus"></i></div></div></div><div class="info"><span>Marketing Leader</span><h4><a href="{{ route('front.team.details') }}">Daniyel Joe</a></h4></div></div></div>
                </div></div></div>
            </div>
        </div>
    </div>

    <div class="process-style-two items default-padding bg-dark text-light">
        <div class="container"><div class="row"><div class="col-lg-6"><div class="site-heading"><h4 class="sub-title">Our Process</h4><h2 class="title">Building great future Together, Be with us</h2></div></div></div></div>
        <div class="container"><div class="process-style-two-ites"><div class="row">
            <div class="col-lg-4 col-md-6 process-style-two-item"><div class="item"><span>01</span><h4>Honesty</h4><p>experience and expertise, I have been recognized through the awards achieved, I am able to customize solutions to meet your specific needs</p></div></div>
            <div class="col-lg-4 col-md-6 process-style-two-item"><div class="item"><span>02</span><h4>Unity</h4><p>experience and expertise, I have been recognized through the awards achieved, I am able to customize solutions to meet your specific needs</p></div></div>
            <div class="col-lg-4 col-md-6 process-style-two-item"><div class="item"><span>03</span><h4>Innovation</h4><p>experience and expertise, I have been recognized through the awards achieved, I am able to customize solutions to meet your specific needs</p></div></div>
        </div></div></div>
    </div>

    <div class="pricing-style-one-area default-padding bg-cover bg-gray" style="background-image: url({{ asset('assets/front/img/shape/3.jpg') }});">
        <div class="container"><div class="row"><div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2"><div class="site-heading text-center"><h4 class="sub-title">Pricing Plan</h4><h2 class="title">Our Pricing packages</h2></div></div></div></div>
        <div class="container"><div class="row">
            <div class="col-lg-4"><div class="pricing-style-one wow fadeInUp"><div class="pricing-header"><h4>Basic Plan</h4><p>The most basic Plan</p><h2>$32</h2><span>Per Month Package</span></div><ul><li><i class="fas fa-check"></i> 100 Days Sitting</li><li><i class="fas fa-check"></i> Market Report Analysis</li><li><i class="fas fa-times"></i> Exclusive Manuals</li><li><i class="fas fa-times"></i> Creative Leadership team</li></ul><a class="btn btn-gradient btn-md animation" href="{{ route('front.contact') }}">Purchase Plan</a></div></div>
            <div class="col-lg-8"><div class="pricing-two-box wow fadeInUp" data-wow-delay="300ms"><div class="row">
                <div class="col-lg-6"><div class="pricing-style-one"><div class="badge">Best Deal</div><div class="pricing-header"><h4>Standard Plan</h4><p>Exclusive For small Business</p><h2>$58</h2><span>Per Month Package</span></div><ul><li><i class="fas fa-check"></i> 100 Days Sitting</li><li><i class="fas fa-check"></i> Market Report Analysis</li><li><i class="fas fa-check"></i> Exclusive Manuals</li><li><i class="fas fa-times"></i> Creative Leadership team</li></ul><a class="btn btn-dark btn-md animation" href="{{ route('front.contact') }}">Purchase Plan</a></div></div>
                <div class="col-lg-6"><div class="pricing-style-one"><div class="pricing-header"><h4>Advanced Plan</h4><p>The most Profitable Plan</p><h2>$99</h2><span>Per Month Package</span></div><ul><li><i class="fas fa-check"></i> 100 Days Sitting</li><li><i class="fas fa-check"></i> Market Report Analysis</li><li><i class="fas fa-check"></i> Exclusive Manuals</li><li><i class="fas fa-check"></i> Creative Leadership team</li></ul><a class="btn btn-dark btn-md animation" href="{{ route('front.contact') }}">Purchase Plan</a></div></div>
            </div></div></div>
        </div></div>
    </div>
@endsection


