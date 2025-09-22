@extends('layouts.front')

@section('title', 'Team')

@section('content')
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
                @foreach (['v4','v5','v1','v2','v3'] as $member)
                    <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp">
                        <div class="team-style-two-item" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                            <div class="thumb">
                                <img src="{{ asset('assets/front/img/team/' . $member . '.jpg') }}" alt="Image Not Found">
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                            <div class="info">
                                <h4><a href="{{ url('/team-details') }}">Team Member</a></h4>
                                <span>Role</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


