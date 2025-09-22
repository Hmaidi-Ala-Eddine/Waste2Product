@extends('layouts.front')

@section('title', 'Pricing')

@section('content')
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Plans & Pricing</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="default-padding">
        <div class="container">
            <div class="row">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="col-lg-4 mb-30">
                        <div class="pricing-style-one text-center bg-gray">
                            <h3>Plan {{ $i }}</h3>
                            <h2 class="price"><sup>$</sup>{{ $i * 29 }}<sub>/mo</sub></h2>
                            <ul class="list-style-one mt-20">
                                <li>Feature A</li>
                                <li>Feature B</li>
                                <li>Feature C</li>
                            </ul>
                            <a class="btn btn-md circle btn-theme mt-20" href="#">Choose Plan</a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection


