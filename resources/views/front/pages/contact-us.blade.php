@extends('layouts.front')

@section('title', 'Contact Us')

@section('content')
    <div class="contact-style-one-area overflow-hidden default-padding">
        <div class="contact-shape">
            <img src="{{ asset('assets/front/img/illustration/14.png') }}" alt="Image Not Found">
        </div>
        <div class="container">
            <div class="row align-center">
                <div class="contact-stye-one col-lg-5 mb-md-50 mb-xs-40">
                    <div class="contact-style-one-info">
                        <h4 class="sub-title">Have Questions?</h4>
                        <h2>Contact Information</h2>
                        <p>Plan upon yet way get cold spot its week. Almost do am or limits hearts. Resolve parties but why she shewing.</p>
                        <ul>
                            <li class="wow fadeInUp">
                                <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="content">
                                    <h5 class="title">Hotline</h5>
                                    <a href="tel:+4733378901">+4733378901</a>
                                </div>
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="300ms">
                                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="info">
                                    <h5 class="title">Our Location</h5>
                                    <p>55 Main Street, The Grand Avenue 2nd Block, <br> New York City</p>
                                </div>
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="500ms">
                                <div class="icon"><i class="fas fa-envelope-open-text"></i></div>
                                <div class="info">
                                    <h5 class="title">Official Email</h5>
                                    <a href="mailto:info@gixus.com">info@gixus.com</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="contact-stye-one col-lg-7 pl-60 pl-md-15 pl-xs-15">
                    <div class="contact-form-style-one">
                        <h2 class="heading">Send us a Message</h2>
                        <form action="#" method="POST" class="contact-form contact-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input class="form-control" name="name" placeholder="Name" type="text">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" name="email" placeholder="Email*" type="email">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" name="phone" placeholder="Phone" type="text">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group comments">
                                        <textarea class="form-control" name="comments" placeholder="Tell Us About Project *"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit">
                                        <i class="fa fa-paper-plane"></i> Get in Touch
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="maps-area bg-gray overflow-hidden">
        <div class="google-maps">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48388.929990966964!2d-74.00332!3d40.711233!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY!5e0!3m2!1sen!2sus!4v1653598669477!5m2!1sen!2sus"></iframe>
        </div>
    </div>
@endsection


