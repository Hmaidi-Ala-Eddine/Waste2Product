@extends('layouts.front')

@section('title', 'Project Details')

@section('content')
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Project Details</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-6 mb-30">
                    <img class="img-fluid" src="{{ asset('assets/front/img/projects/5.jpg') }}" alt="Project">
                </div>
                <div class="col-lg-6">
                    <h2>Cyber Security</h2>
                    <p>
                        Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                    </p>
                    <ul class="list-style-one mt-20">
                        <li>Technology, IT</li>
                        <li>Firewall, Network</li>
                        <li>Support, Consulting</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


