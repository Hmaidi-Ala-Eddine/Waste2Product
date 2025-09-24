@extends('layouts.back')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Alerts</h6>
        <p class="text-sm">Alerts are available for any length of text, as well as an optional dismiss button.</p>
      </div>
      <div class="card-body p-3">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-primary alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple primary alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-secondary alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple secondary alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-success alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple success alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-danger alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple danger alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-warning alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple warning alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-info alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple info alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-light alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple light alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-dark alert-dismissible text-white" role="alert">
              <span class="text-sm">A simple dark alert with <a href="javascript:;" class="alert-link text-white">an example link</a>. Give it a click if you like.</span>
              <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Notifications</h6>
        <p class="text-sm">Notifications on this page use Toasts from Bootstrap. Read more details <a href="https://getbootstrap.com/docs/5.0/components/toasts/" target="_blank">here</a>.</p>
      </div>
      <div class="card-body p-3">
        <div class="row">
          <div class="col-lg-3 col-sm-6 col-12">
            <button class="btn bg-gradient-success w-100 mb-0 toast-btn" type="button" data-target="successToast">Success</button>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 mt-sm-0 mt-2">
            <button class="btn bg-gradient-info w-100 mb-0 toast-btn" type="button" data-target="infoToast">Info</button>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 mt-lg-0 mt-2">
            <button class="btn bg-gradient-warning w-100 mb-0 toast-btn" type="button" data-target="warningToast">Warning</button>
          </div>
          <div class="col-lg-3 col-sm-6 col-12 mt-lg-0 mt-2">
            <button class="btn bg-gradient-danger w-100 mb-0 toast-btn" type="button" data-target="dangerToast">Danger</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toast Notifications -->
<div class="position-fixed bottom-1 end-1 z-index-2">
  <div class="toast fade hide p-2 bg-white" role="alert" aria-live="assertive" id="successToast" aria-atomic="true">
    <div class="toast-header border-0">
      <i class="material-symbols-rounded text-success me-2">check</i>
      <span class="me-auto font-weight-bold">Material Dashboard</span>
      <small class="text-body">11 mins ago</small>
      <i class="fas fa-times text-md ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
    </div>
    <hr class="horizontal dark m-0">
    <div class="toast-body">
      Hello, world! This is a notification message.
    </div>
  </div>
  <div class="toast fade hide p-2 mt-2 bg-gradient-info" role="alert" aria-live="assertive" id="infoToast" aria-atomic="true">
    <div class="toast-header bg-transparent border-0">
      <i class="material-symbols-rounded text-white me-2">notifications</i>
      <span class="me-auto text-white font-weight-bold">Material Dashboard</span>
      <small class="text-white">11 mins ago</small>
      <i class="fas fa-times text-md text-white ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
    </div>
    <hr class="horizontal light m-0">
    <div class="toast-body text-white">
      Hello, world! This is a notification message.
    </div>
  </div>
  <div class="toast fade hide p-2 mt-2 bg-gradient-warning" role="alert" aria-live="assertive" id="warningToast" aria-atomic="true">
    <div class="toast-header bg-transparent border-0">
      <i class="material-symbols-rounded text-white me-2">travel_explore</i>
      <span class="me-auto text-white font-weight-bold">Material Dashboard</span>
      <small class="text-white">11 mins ago</small>
      <i class="fas fa-times text-md text-white ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
    </div>
    <hr class="horizontal light m-0">
    <div class="toast-body text-white">
      Hello, world! This is a notification message.
    </div>
  </div>
  <div class="toast fade hide p-2 mt-2 bg-gradient-danger" role="alert" aria-live="assertive" id="dangerToast" aria-atomic="true">
    <div class="toast-header bg-transparent border-0">
      <i class="material-symbols-rounded text-white me-2">campaign</i>
      <span class="me-auto text-white font-weight-bold">Material Dashboard</span>
      <small class="text-white">11 mins ago</small>
      <i class="fas fa-times text-md text-white ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
    </div>
    <hr class="horizontal light m-0">
    <div class="toast-body text-white">
      Hello, world! This is a notification message.
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Toast button functionality
  const toastButtons = document.querySelectorAll('.toast-btn');
  
  toastButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const toastElement = document.getElementById(targetId);
      
      if (toastElement) {
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
      }
    });
  });
});
</script>
@endpush
