@extends('layouts.back')

@section('title', 'Interactive Map')
@section('page-title', 'Map')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Marker Cluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<style>
  #map {
    height: 600px;
    width: 100%;
    border-radius: 0.75rem;
    box-shadow: 0 20px 27px 0 rgba(0,0,0,0.05);
  }
  .map-control-panel {
    background: white;
    padding: 20px;
    border-radius: 0.75rem;
    box-shadow: 0 20px 27px 0 rgba(0,0,0,0.05);
    margin-bottom: 20px;
  }
  .legend {
    background: white;
    padding: 15px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
  .legend-item {
    margin: 8px 0;
    display: flex;
    align-items: center;
  }
  .legend-color {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 10px;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.2);
  }
  .filter-btn {
    margin: 5px;
  }
  .stat-card {
    cursor: pointer;
    transition: transform 0.2s;
  }
  .stat-card:hover {
    transform: translateY(-5px);
  }
  .leaflet-popup-content-wrapper {
    border-radius: 8px;
  }
  .popup-header {
    font-weight: bold;
    color: #344767;
    margin-bottom: 8px;
    font-size: 14px;
  }
  .popup-info {
    font-size: 12px;
    margin: 4px 0;
  }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-12">
    <div class="mb-4">
      <h3 class="mb-0 h4 font-weight-bolder">Waste Collection Map</h3>
      <p class="mb-0 text-sm">
        <i class="material-symbols-rounded text-sm">location_on</i>
        Real-time tracking of waste requests and collectors across Tunisia
      </p>
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card stat-card" data-filter="all">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
              <h5 class="font-weight-bolder mb-0" id="total-requests">
                <div class="spinner-border spinner-border-sm" role="status"></div>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="material-symbols-rounded opacity-10">recycling</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card stat-card" data-filter="pending">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending</p>
              <h5 class="font-weight-bolder mb-0 text-warning" id="pending-requests">
                <div class="spinner-border spinner-border-sm" role="status"></div>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
              <i class="material-symbols-rounded opacity-10">pending</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card stat-card" data-filter="accepted">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Accepted</p>
              <h5 class="font-weight-bolder mb-0 text-info" id="accepted-requests">
                <div class="spinner-border spinner-border-sm" role="status"></div>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
              <i class="material-symbols-rounded opacity-10">check_circle</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card stat-card" data-filter="collected">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Collected</p>
              <h5 class="font-weight-bolder mb-0 text-success" id="collected-requests">
                <div class="spinner-border spinner-border-sm" role="status"></div>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
              <i class="material-symbols-rounded opacity-10">done_all</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Map Controls -->
<div class="row mb-4">
  <div class="col-12">
    <div class="map-control-panel">
      <div class="row align-items-center mb-3">
        <div class="col-md-4">
          <h6 class="mb-0">
            <i class="material-symbols-rounded text-sm">tune</i> Map Controls
          </h6>
        </div>
        <div class="col-md-8 text-md-end">
          <button class="btn btn-sm bg-gradient-primary filter-btn active" data-layer="requests">
            <i class="material-symbols-rounded text-sm">delete</i> Requests
          </button>
          <button class="btn btn-sm bg-gradient-success filter-btn active" data-layer="collectors">
            <i class="material-symbols-rounded text-sm">local_shipping</i> Collectors
          </button>
          <button class="btn btn-sm bg-gradient-info filter-btn" id="toggle-heatmap">
            <i class="material-symbols-rounded text-sm">thermostat</i> Heat Map
          </button>
          <button class="btn btn-sm bg-gradient-warning filter-btn" id="fullscreen-btn">
            <i class="material-symbols-rounded text-sm">fullscreen</i> Fullscreen
          </button>
          <button class="btn btn-sm bg-gradient-secondary filter-btn" id="reset-view">
            <i class="material-symbols-rounded text-sm">my_location</i> Reset View
          </button>
          <button class="btn btn-sm bg-gradient-dark filter-btn" id="refresh-map">
            <i class="material-symbols-rounded text-sm">refresh</i> Refresh
          </button>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="material-symbols-rounded text-sm">search</i></span>
            <input type="text" class="form-control" id="search-location" placeholder="Search governorate...">
          </div>
        </div>
        <div class="col-md-6 mt-2 mt-md-0">
          <select class="form-select form-select-sm" id="zoom-to-state">
            <option value="">🎯 Zoom to Governorate...</option>
            <option value="tunis">Tunis</option>
            <option value="ariana">Ariana</option>
            <option value="ben_arous">Ben Arous</option>
            <option value="manouba">Manouba</option>
            <option value="nabeul">Nabeul</option>
            <option value="zaghouan">Zaghouan</option>
            <option value="bizerte">Bizerte</option>
            <option value="beja">Beja</option>
            <option value="jendouba">Jendouba</option>
            <option value="kef">Kef</option>
            <option value="siliana">Siliana</option>
            <option value="sousse">Sousse</option>
            <option value="monastir">Monastir</option>
            <option value="mahdia">Mahdia</option>
            <option value="sfax">Sfax</option>
            <option value="kairouan">Kairouan</option>
            <option value="kasserine">Kasserine</option>
            <option value="sidi_bouzid">Sidi Bouzid</option>
            <option value="gabes">Gabes</option>
            <option value="medenine">Medenine</option>
            <option value="tataouine">Tataouine</option>
            <option value="gafsa">Gafsa</option>
            <option value="tozeur">Tozeur</option>
            <option value="kebili">Kebili</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Map Container -->
<div class="row">
  <div class="col-lg-9 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Tunisia Waste Collection Map</h6>
        <p class="text-xs mb-0">Showing all waste requests and collectors</p>
      </div>
      <div class="card-body">
        <div id="map"></div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 mb-4">
    <!-- Legend -->
    <div class="card mb-3">
      <div class="card-header pb-0">
        <h6 class="mb-0">Legend</h6>
      </div>
      <div class="card-body">
        <div class="legend">
          <p class="text-xs font-weight-bold mb-2">Waste Requests:</p>
          <div class="legend-item">
            <div class="legend-color" style="background: #fb8500;"></div>
            <span class="text-xs">Pending</span>
          </div>
          <div class="legend-item">
            <div class="legend-color" style="background: #3a86ff;"></div>
            <span class="text-xs">Accepted</span>
          </div>
          <div class="legend-item">
            <div class="legend-color" style="background: #17ad37;"></div>
            <span class="text-xs">Collected</span>
          </div>
          <hr class="horizontal dark my-2">
          <p class="text-xs font-weight-bold mb-2">Collectors:</p>
          <div class="legend-item">
            <div class="legend-color" style="background: #344767;"></div>
            <span class="text-xs">Active Collector</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <div class="card mb-3">
      <div class="card-header pb-0">
        <h6 class="mb-0">⌨️ Keyboard Shortcuts</h6>
      </div>
      <div class="card-body">
        <div class="text-xs">
          <div class="d-flex justify-content-between mb-2">
            <span><kbd>F</kbd></span>
            <span>Fullscreen</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span><kbd>H</kbd></span>
            <span>Heat Map</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span><kbd>R</kbd></span>
            <span>Refresh</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span><kbd>ESC</kbd></span>
            <span>Reset View</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="card">
      <div class="card-header pb-0">
        <h6 class="mb-0">Quick Stats</h6>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <span class="text-xs">Total Collectors:</span>
          <span class="text-xs font-weight-bold" id="total-collectors">
            <div class="spinner-border spinner-border-sm" role="status"></div>
          </span>
        </div>
        <div class="d-flex justify-content-between mb-3">
          <span class="text-xs">Total Waste Collected:</span>
          <span class="text-xs font-weight-bold" id="total-waste">
            <div class="spinner-border spinner-border-sm" role="status"></div>
          </span>
        </div>
        <hr class="horizontal dark">
        <p class="text-xs font-weight-bold mb-2">By Governorate:</p>
        <div id="state-stats" class="text-xs">
          <div class="spinner-border spinner-border-sm" role="status"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Marker Cluster JS -->
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
  let map;
  let requestsLayer;
  let collectorsLayer;
  let markerClusters;
  let currentFilter = 'all';

  // Initialize map
  document.addEventListener('DOMContentLoaded', function() {
    // Create map centered on Tunisia
    map = L.map('map').setView([36.8065, 10.1815], 7);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19
    }).addTo(map);

    // Create marker cluster group
    markerClusters = L.markerClusterGroup({
      maxClusterRadius: 80,
      spiderfyOnMaxZoom: true,
      showCoverageOnHover: false
    });

    // Create layer groups
    requestsLayer = L.layerGroup().addTo(map);
    collectorsLayer = L.layerGroup().addTo(map);

    // Load initial data
    loadStatistics();
    loadWasteRequests();
    loadCollectors();

    // Filter button handlers
    document.querySelectorAll('.filter-btn[data-layer]').forEach(btn => {
      btn.addEventListener('click', function() {
        this.classList.toggle('active');
        const layer = this.dataset.layer;
        
        if (layer === 'requests') {
          if (this.classList.contains('active')) {
            map.addLayer(requestsLayer);
          } else {
            map.removeLayer(requestsLayer);
          }
        } else if (layer === 'collectors') {
          if (this.classList.contains('active')) {
            map.addLayer(collectorsLayer);
          } else {
            map.removeLayer(collectorsLayer);
          }
        }
      });
    });

    // Stat card filter handlers
    document.querySelectorAll('.stat-card').forEach(card => {
      card.addEventListener('click', function() {
        const filter = this.dataset.filter;
        currentFilter = filter;
        loadWasteRequests(filter);
      });
    });

    // Refresh button
    document.getElementById('refresh-map').addEventListener('click', function() {
      const btn = this;
      btn.innerHTML = '<i class="material-symbols-rounded text-sm">sync</i> Refreshing...';
      btn.disabled = true;
      
      loadStatistics();
      loadWasteRequests(currentFilter);
      loadCollectors();
      
      setTimeout(() => {
        btn.innerHTML = '<i class="material-symbols-rounded text-sm">refresh</i> Refresh';
        btn.disabled = false;
      }, 1500);
    });

    // Zoom to governorate
    document.getElementById('zoom-to-state').addEventListener('change', function() {
      const state = this.value;
      if (state) {
        const coords = getGovernorateCoords(state);
        if (coords) {
          map.flyTo([coords.lat, coords.lng], 10, {
            duration: 1.5,
            easeLinearity: 0.5
          });
        }
      }
    });

    // Search location
    document.getElementById('search-location').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      if (searchTerm.length > 2) {
        // Find matching governorate
        const select = document.getElementById('zoom-to-state');
        const options = Array.from(select.options);
        const match = options.find(opt => opt.text.toLowerCase().includes(searchTerm));
        
        if (match && match.value) {
          select.value = match.value;
          const coords = getGovernorateCoords(match.value);
          if (coords) {
            map.flyTo([coords.lat, coords.lng], 10, { duration: 1 });
          }
        }
      }
    });

    // Fullscreen toggle
    let isFullscreen = false;
    document.getElementById('fullscreen-btn').addEventListener('click', function() {
      const mapCard = document.querySelector('#map').closest('.card');
      
      if (!isFullscreen) {
        mapCard.style.position = 'fixed';
        mapCard.style.top = '0';
        mapCard.style.left = '0';
        mapCard.style.width = '100vw';
        mapCard.style.height = '100vh';
        mapCard.style.zIndex = '9999';
        mapCard.style.margin = '0';
        document.getElementById('map').style.height = 'calc(100vh - 100px)';
        this.innerHTML = '<i class="material-symbols-rounded text-sm">fullscreen_exit</i> Exit';
        isFullscreen = true;
      } else {
        mapCard.style.position = '';
        mapCard.style.top = '';
        mapCard.style.left = '';
        mapCard.style.width = '';
        mapCard.style.height = '';
        mapCard.style.zIndex = '';
        mapCard.style.margin = '';
        document.getElementById('map').style.height = '600px';
        this.innerHTML = '<i class="material-symbols-rounded text-sm">fullscreen</i> Fullscreen';
        isFullscreen = false;
      }
      
      setTimeout(() => map.invalidateSize(), 100);
    });

    // Reset view button - return to Tunisia overview
    document.getElementById('reset-view').addEventListener('click', function() {
      map.flyTo([36.8065, 10.1815], 7, {
        duration: 1.5,
        easeLinearity: 0.5
      });
      document.getElementById('zoom-to-state').value = '';
      document.getElementById('search-location').value = '';
    });

    // Heatmap toggle (visual density indicator)
    let heatmapEnabled = false;
    document.getElementById('toggle-heatmap').addEventListener('click', function() {
      heatmapEnabled = !heatmapEnabled;
      
      if (heatmapEnabled) {
        this.classList.add('active');
        this.innerHTML = '<i class="material-symbols-rounded text-sm">thermostat</i> Hide Heat';
        
        // Increase marker sizes and add glow - access markers in cluster
        markerClusters.eachLayer(layer => {
          if (layer.setRadius) {
            layer.setRadius(12);
            layer.setStyle({ 
              weight: 3,
              fillOpacity: 1
            });
          }
        });
        
        // Also enhance collector markers
        collectorsLayer.eachLayer(layer => {
          if (layer.setIcon) {
            const icon = L.divIcon({
              className: 'custom-div-icon',
              html: `<div style="background-color: #344767; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                <i class="material-symbols-rounded text-white" style="font-size: 20px;">local_shipping</i>
              </div>`,
              iconSize: [40, 40],
              iconAnchor: [20, 20]
            });
            layer.setIcon(icon);
          }
        });
      } else {
        this.classList.remove('active');
        this.innerHTML = '<i class="material-symbols-rounded text-sm">thermostat</i> Heat Map';
        
        // Reset marker sizes
        markerClusters.eachLayer(layer => {
          if (layer.setRadius) {
            layer.setRadius(8);
            layer.setStyle({ 
              weight: 2,
              fillOpacity: 0.8
            });
          }
        });
        
        // Reset collector markers
        collectorsLayer.eachLayer(layer => {
          if (layer.setIcon) {
            const icon = L.divIcon({
              className: 'custom-div-icon',
              html: `<div style="background-color: #344767; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                <i class="material-symbols-rounded text-white" style="font-size: 16px;">local_shipping</i>
              </div>`,
              iconSize: [30, 30],
              iconAnchor: [15, 15]
            });
            layer.setIcon(icon);
          }
        });
      }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      // F key - Fullscreen
      if (e.key === 'f' || e.key === 'F') {
        if (!e.target.matches('input, textarea')) {
          e.preventDefault();
          document.getElementById('fullscreen-btn').click();
        }
      }
      // R key - Refresh
      if (e.key === 'r' || e.key === 'R') {
        if (!e.target.matches('input, textarea')) {
          e.preventDefault();
          document.getElementById('refresh-map').click();
        }
      }
      // H key - Heatmap
      if (e.key === 'h' || e.key === 'H') {
        if (!e.target.matches('input, textarea')) {
          e.preventDefault();
          document.getElementById('toggle-heatmap').click();
        }
      }
      // ESC key - Reset view
      if (e.key === 'Escape') {
        document.getElementById('reset-view').click();
      }
    });
  });

  // Helper function to get governorate coordinates
  function getGovernorateCoords(state) {
    const coords = {
      'tunis': {lat: 36.8065, lng: 10.1815},
      'ariana': {lat: 36.8625, lng: 10.1956},
      'ben_arous': {lat: 36.7538, lng: 10.2297},
      'manouba': {lat: 36.8089, lng: 10.0976},
      'nabeul': {lat: 36.4516, lng: 10.7361},
      'zaghouan': {lat: 36.4028, lng: 10.1433},
      'bizerte': {lat: 37.2746, lng: 9.8739},
      'beja': {lat: 36.7256, lng: 9.1817},
      'jendouba': {lat: 36.5011, lng: 8.7803},
      'kef': {lat: 36.1743, lng: 8.7049},
      'siliana': {lat: 36.0853, lng: 9.3704},
      'sousse': {lat: 35.8256, lng: 10.6369},
      'monastir': {lat: 35.7772, lng: 10.8263},
      'mahdia': {lat: 35.5047, lng: 11.0622},
      'sfax': {lat: 34.7406, lng: 10.7603},
      'kairouan': {lat: 35.6781, lng: 10.0963},
      'kasserine': {lat: 35.1676, lng: 8.8360},
      'sidi_bouzid': {lat: 35.0381, lng: 9.4858},
      'gabes': {lat: 33.8815, lng: 10.0982},
      'medenine': {lat: 33.3549, lng: 10.5055},
      'tataouine': {lat: 32.9297, lng: 10.4517},
      'gafsa': {lat: 34.4250, lng: 8.7842},
      'tozeur': {lat: 33.9197, lng: 8.1335},
      'kebili': {lat: 33.7051, lng: 8.9690}
    };
    return coords[state];
  }

  // Load statistics
  function loadStatistics() {
    fetch('{{ route("admin.map.statistics") }}')
      .then(response => response.json())
      .then(data => {
        document.getElementById('total-requests').textContent = data.total_requests;
        document.getElementById('pending-requests').textContent = data.pending_requests;
        document.getElementById('accepted-requests').textContent = data.accepted_requests;
        document.getElementById('collected-requests').textContent = data.collected_requests;
        document.getElementById('total-collectors').textContent = data.total_collectors;
        document.getElementById('total-waste').textContent = parseFloat(data.total_waste_kg || 0).toFixed(2) + ' kg';
        
        // State statistics - fixed to handle array properly
        let stateHtml = '';
        const states = data.requests_by_state || [];
        
        if (states.length > 0) {
          states.slice(0, 5).forEach(item => {
            stateHtml += `
              <div class="d-flex justify-content-between mb-2">
                <span class="text-capitalize">${item.state}:</span>
                <span class="font-weight-bold">${item.count} (${parseFloat(item.weight || 0).toFixed(1)}kg)</span>
              </div>
            `;
          });
          document.getElementById('state-stats').innerHTML = stateHtml;
        } else {
          document.getElementById('state-stats').innerHTML = '<p class="text-xs text-secondary mb-0">No data available</p>';
        }
      })
      .catch(error => {
        console.error('Error loading statistics:', error);
        document.getElementById('state-stats').innerHTML = '<p class="text-xs text-danger mb-0">Error loading data</p>';
      });
  }

  // Load waste requests
  function loadWasteRequests(status = 'all') {
    const url = status === 'all' 
      ? '{{ route("admin.map.waste-requests") }}'
      : '{{ route("admin.map.waste-requests") }}?status=' + status;

    fetch(url)
      .then(response => response.json())
      .then(requests => {
        requestsLayer.clearLayers();
        markerClusters.clearLayers();

        requests.forEach(request => {
          const statusColors = {
            'pending': '#fb8500',
            'accepted': '#3a86ff',
            'collected': '#17ad37',
            'cancelled': '#e63946'
          };

          const color = statusColors[request.status] || '#6c757d';

          const marker = L.circleMarker([request.latitude, request.longitude], {
            radius: 8,
            fillColor: color,
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
          });

          const popupContent = `
            <div class="popup-content">
              <div class="popup-header">🗑️ ${request.waste_type.toUpperCase()}</div>
              <div class="popup-info"><strong>Customer:</strong> ${request.customer_name}</div>
              <div class="popup-info"><strong>Quantity:</strong> ${request.quantity} kg</div>
              <div class="popup-info"><strong>Location:</strong> ${request.state}</div>
              <div class="popup-info"><strong>Address:</strong> ${request.address}</div>
              <div class="popup-info"><strong>Status:</strong> <span class="badge badge-sm" style="background: ${color}; color: white;">${request.status}</span></div>
              <div class="popup-info"><strong>Collector:</strong> ${request.collector_name}</div>
              <div class="popup-info"><strong>Created:</strong> ${request.created_at}</div>
              ${request.description ? `<div class="popup-info mt-2"><em>${request.description}</em></div>` : ''}
            </div>
          `;

          marker.bindPopup(popupContent);
          markerClusters.addLayer(marker);
        });

        requestsLayer.addLayer(markerClusters);
        console.log(`✅ Loaded ${requests.length} waste requests`);
      })
      .catch(error => console.error('Error loading waste requests:', error));
  }

  // Load collectors
  function loadCollectors() {
    fetch('{{ route("admin.map.collectors") }}')
      .then(response => response.json())
      .then(collectors => {
        collectorsLayer.clearLayers();

        collectors.forEach(collector => {
          const icon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: #344767; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
              <i class="material-symbols-rounded text-white" style="font-size: 16px;">local_shipping</i>
            </div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
          });

          const marker = L.marker([collector.latitude, collector.longitude], { icon: icon });

          const popupContent = `
            <div class="popup-content">
              <div class="popup-header">🚚 ${collector.name}</div>
              ${collector.company_name ? `<div class="popup-info"><strong>Company:</strong> ${collector.company_name}</div>` : ''}
              <div class="popup-info"><strong>Email:</strong> ${collector.email}</div>
              <div class="popup-info"><strong>Vehicle:</strong> ${collector.vehicle_type}</div>
              <div class="popup-info"><strong>Capacity:</strong> ${collector.capacity_kg} kg</div>
              <div class="popup-info"><strong>Collections:</strong> ${collector.total_collections}</div>
              <div class="popup-info"><strong>Rating:</strong> ⭐ ${collector.rating || 'N/A'}</div>
              <div class="popup-info mt-2"><strong>Service Areas:</strong><br>${collector.service_areas.join(', ')}</div>
            </div>
          `;

          marker.bindPopup(popupContent);
          collectorsLayer.addLayer(marker);
        });

        console.log(`✅ Loaded ${collectors.length} collectors`);
      })
      .catch(error => console.error('Error loading collectors:', error));
  }
</script>
@endpush
