@extends('layouts.back')

@section('title', 'Test Page')
@section('page-title', 'Test Page')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Test Page</h6>
          </div>
        </div>
      </div>
      
      <div class="card-body px-0 pb-2">
        <div class="p-4">
          <h3>Test Page Working!</h3>
          <p>If you can see this, the layout is working correctly.</p>
          <p>Current user: {{ Auth::user() ? Auth::user()->name : 'Not logged in' }}</p>
          <p>User role: {{ Auth::user() ? Auth::user()->role : 'N/A' }}</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

