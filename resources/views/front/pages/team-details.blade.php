@extends('layouts.front')
@section('title', 'Team Details')
@section('content')
    <div class="default-padding">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-5 mb-30">
                    <img class="img-fluid" src="{{ asset('assets/front/img/team/v1.jpg') }}" alt="Team">
                </div>
                <div class="col-lg-7">
                    <h2>Team Member Name</h2>
                    <p>Short bio / description here.</p>
                    <ul class="list-style-one mt-20">
                        <li>Role: Senior Consultant</li>
                        <li>Experience: 10+ years</li>
                        <li>Specialty: Finance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


