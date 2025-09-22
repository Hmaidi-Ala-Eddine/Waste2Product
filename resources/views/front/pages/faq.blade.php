@extends('layouts.front')

@section('title', 'FAQ')

@section('content')
    <div class="faq-style-one-area chooseus-style-two-area default-padding">
        <div class="container">
            <div class="faq-style-two-items chooseus-style-two-items bg-cover" style="background-image: url({{ asset('assets/front/img/shape/2.jpg') }});">
                <div class="row">
                    <div class="col-xl-6 pr-50 pr-md-15 pr-xs-15">
                        <div class="fun-fact-style-info">
                            <h2 class="title">Whatever plan we got you covered</h2>
                            <div class="fun-fact-card-two mt-40">
                                <h4 class="sub-title">Why Choose Us</h4>
                                <div class="counter-title">
                                    <div class="counter">
                                        <div class="timer" data-to="56" data-speed="2000">56</div>
                                        <div class="operator">K</div>
                                    </div>
                                </div>
                                <span class="medium">Clients around the world</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="choose-us-style-two">
                            <div class="accordion" id="faqAccordion">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div class="accordion-item accordion-style-one">
                                        <h2 class="accordion-header" id="heading{{ $i }}">
                                            <button class="accordion-button {{ $i === 1 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $i }}">
                                                Sample question {{ $i }}?
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}" aria-labelledby="heading{{ $i }}" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                <p>
                                                    Sample answer for question {{ $i }}.
                                                </p>
                                                <ul class="list-style-one">
                                                    <li>Point A</li>
                                                    <li>Point B</li>
                                                    <li>Point C</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


