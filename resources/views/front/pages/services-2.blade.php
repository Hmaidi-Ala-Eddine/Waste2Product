@extends('layouts.front')
@section('title', 'Services Two')
@section('content')
    <div class="default-padding">
        <div class="container">
            <div class="row">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="col-lg-4 mb-30">
                        <div class="feature-style-two wow fadeInRight">
                            <div class="thumb">
                                <img src="{{ asset('assets/front/img/features/' . (($i - 1) % 3 + 1) . '.jpg') }}" alt="Thumb">
                                <div class="title">
                                    <div class="top">
                                        <img src="{{ asset('assets/front/img/icon/' . (12 + (($i - 1) % 6) + 1) . '.png') }}" alt="Icon Not Found">
                                        <h4><a href="{{ url('/services-details') }}">Service {{ $i }}</a></h4>
                                    </div>
                                    <a href="{{ url('/services-details') }}"><i class="fas fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection


