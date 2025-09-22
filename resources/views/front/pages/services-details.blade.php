@extends('layouts.front')

@section('title', 'Service Details')

@section('content')
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Service Details</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-6 mb-30">
                    <img class="img-fluid" src="{{ asset('assets/front/img/features/1.jpg') }}" alt="Service">
                </div>
                <div class="col-lg-6">
                    <h2>Analytic Solutions</h2>
                    <p>
                        Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                    </p>
                    <ul class="list-style-one mt-20">
                        <li>Business Management consultation</li>
                        <li>Team Building Leadership</li>
                        <li>Growth Method Analysis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


