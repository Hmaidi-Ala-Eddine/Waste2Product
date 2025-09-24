@extends('layouts.back')

@section('title', 'Map')
@section('page-title', 'Map')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card h-100">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
          <h5 class="text-white text-capitalize ps-3">Vector Map</h5>
        </div>
      </div>
      <div class="card-body">
        <div id="vector-map" class="mt-5 min-height-500"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/back/js/plugins/world.js') }}"></script>
<script>
  // World map
  var map = new jsVectorMap({
    selector: "#vector-map",
    map: "world_merc",
    zoomOnScroll: false,
    zoomButtons: false,
    markersSelectable: true,
    backgroundColor: "transparent",
    regionStyle: {
      initial: {
        fill: "#dee2e6",
        "fill-opacity": 0.9,
        stroke: "none",
        "stroke-width": 0,
        "stroke-opacity": 0
      }
    },
    markers: [{
        name: "USA",
        coords: [40.71, -74.00],
        style: {
          fill: "#E91E63"
        }
      },
      {
        name: "Germany",
        coords: [52.52, 13.40],
        style: {
          fill: "#4CAF50"
        }
      },
      {
        name: "Brazil",
        coords: [-14.23, -51.92],
        style: {
          fill: "#FF9800"
        }
      },
      {
        name: "France",
        coords: [46.22, 2.21],
        style: {
          fill: "#2196F3"
        }
      },
      {
        name: "RO",
        coords: [45.94, 24.97],
        style: {
          fill: "#9C27B0"
        }
      },
      {
        name: "Russia",
        coords: [61.52, 105.31],
        style: {
          fill: "#607D8B"
        }
      }
    ],
    markerStyle: {
      initial: {
        r: 9,
        "stroke-width": 7,
        "stroke-opacity": 0.4,
        fill: "#FFF"
      },
      hover: {
        fill: "#FFF",
        stroke: "#FFF"
      }
    }
  });
</script>
@endpush
