@extends('Layouts.Base')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Program Gis</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->
  <style>
    body {
      background-color: #f4f7fa;
      font-family: 'Roboto', sans-serif;
    }
    #map {
      height: 80vh;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .popup-content {
      font-family: 'Arial', sans-serif;
      text-align: center;
      color: #333;
    }
    .popup-card {
      width: 300px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    .popup-card .card-header {
      background-color: #007bff;
      color: #fff;
      font-size: 18px;
      padding: 15px;
      border-bottom: 2px solid #0056b3;
    }
    .popup-card .card-body {
      padding: 15px;
      background-color: #f9f9f9;
      border-top: 1px solid #ddd;
    }
    .popup-description {
      font-size: 14px;
      color: #555;
    }
    .popup-buttons {
      margin-top: 15px;
      display: flex;
      justify-content: space-between;
    }
    .btn-custom {
      padding: 10px 14px;
      font-size: 15px;
      border-radius: 5px;
      transition: transform 0.3s;
      width: 45%;
    }
    .btn-custom:hover {
      transform: scale(1.1);
    }
    .btn-warning {
      background-color: #f0ad4e;
      border-color: #f0ad4e;
    }
    .btn-warning:hover {
      background-color: #ec971f;
      border-color: #d58512;
    }
    .btn-danger {
      background-color: #d9534f;
      border-color: #d9534f;
    }
    .btn-danger:hover {
      background-color: #c9302c;
      border-color: #ac2925;
    }
    .search-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 20px 0;
      background-color: white;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .search-container input {
      width: 300px;
      border-radius: 5px;
      padding: 10px;
    }
    .search-container button {
      margin-left: 10px;
    }
    .modal-content {
      border-radius: 10px;
    }
    .leaflet-popup-content-wrapper {
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
  }
  .leaflet-popup-content {
    margin: 0;
    padding: 0;
  }
  .popup-custom {
    width: 250px;
    padding: 15px;
  }
  .popup-custom h5 {
    font-weight: bold;
    margin-bottom: 10px;
    color: #007bff;
  }
  .popup-custom p {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
  }
  .popup-custom .popup-buttons {
    display: flex;
    justify-content: space-between;
  }
  .popup-custom .btn {
    width: 48%;
    font-size: 13px;
    border-radius: 8px;
  }

  /* Custom control untuk layer map */
  .leaflet-control-layers {
    background: white;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0,0,0,0.15);
    font-size: 14px;
  }
  .leaflet-control-layers-toggle {
    background-image: url('https://cdn-icons-png.flaticon.com/512/535/535234.png');
    background-size: 30px 30px;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
  }

  .input-group {
  gap: 10px;
}
.input-group input {
  flex-grow: 1;
}
.input-group button {
  flex-shrink: 0;
  border-radius: 0 10px 10px 0;
}
  </style>
</head>
<body>

<!-- Input dan Tombol Pencarian -->
<div class="container search-container">
  <div class="input-group">
    <input type="text" id="search" class="form-control" placeholder="Cari nama lokasi...">
    <button onclick="cariLokasi()" class="btn btn-primary">Cari</button>
    <button onclick="temukanSaya()" class="btn btn-success">Lokasi saat ini</button>
  </div>
</div>


<!-- Modal Tambah Lokasi Baru -->
<div class="modal" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Tambah Lokasi Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form id="addForm">
          <div class="mb-3">
            <label for="addNama" class="form-label">Nama Lokasi</label>
            <input type="text" class="form-control" id="addNama" required>
          </div>
          <div class="mb-3">
            <label for="addDeskripsi" class="form-label">Deskripsi Lokasi</label>
            <textarea class="form-control" id="addDeskripsi" rows="3"></textarea>
          </div>
          <input type="hidden" id="addLatitude">
          <input type="hidden" id="addLongitude">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveAddBtn">Simpan Lokasi</button>
      </div>
    </div>
  </div>
</div>


<!-- Peta -->
<div id="map"></div>

<!-- Modal Edit Lokasi -->
<div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Lokasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <div class="mb-3">
            <label for="editNama" class="form-label">Nama Lokasi</label>
            <input type="text" class="form-control" id="editNama" required>
          </div>
          <div class="mb-3">
            <label for="editDeskripsi" class="form-label">Deskripsi Lokasi</label>
            <textarea class="form-control" id="editDeskripsi" rows="3" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="saveEditBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus lokasi ini?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Inisialisasi peta
var map = L.map('map').setView([-0.8871, 119.8604], 13);

// Layer Peta Biasa
var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Layer Satelit
var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 19,
  attribution: 'Tiles © Esri'
});

// Basemap pilihan
var baseMaps = {
  "Peta Biasa": osm,
  "Peta Satelit": satellite
};

// Tambahkan kontrol layer ke map
L.control.layers(baseMaps).addTo(map);

// Tambahkan geocoder
L.Control.geocoder().addTo(map);

// Ambil semua lokasi dari database dan tampilkan sebagai marker
var locations = @json($lokasi);

var markers = [];

function loadMarkers(data) {
  markers.forEach(m => map.removeLayer(m));
  markers = [];

  data.forEach(function(lokasi) {
    var marker = L.marker([lokasi.latitude, lokasi.longitude]).addTo(map);

    marker.bindPopup(`
      <div class="popup-custom">
        <h5>${lokasi.nama}</h5>
        <p>${lokasi.deskripsi}</p>
        <div class="popup-buttons">
          <button class="btn btn-warning btn-sm" onclick="editLokasi(${lokasi.id}, '${lokasi.nama}', '${lokasi.deskripsi}')">Edit</button>
          <button class="btn btn-danger btn-sm" onclick="hapusLokasi(${lokasi.id})">Hapus</button>
        </div>
      </div>
    `);

    marker.on('click', function() {
      marker.openPopup();
    });

    markers.push(marker);
  });

}


// Pertama load semua lokasi
loadMarkers(locations);

// Klik di map untuk tambah marker
map.on('click', function(e) {
  var lat = e.latlng.lat;
  var lng = e.latlng.lng;
  
  // Simpan koordinat sementara
  $('#addLatitude').val(lat);
  $('#addLongitude').val(lng);
  
  // Buka modal tambah lokasi
  $('#addModal').modal('show');
});


// Fungsi edit lokasi menggunakan modal
function editLokasi(id, namaAwal, deskripsiAwal) {
  currentLocationId = id;
  $('#editNama').val(namaAwal);
  $('#editDeskripsi').val(deskripsiAwal);
  $('#editModal').modal('show');
}

// Fungsi untuk menyimpan perubahan
$('#saveEditBtn').click(function() {
  var nama = $('#editNama').val();
  var deskripsi = $('#editDeskripsi').val();

  if (nama && deskripsi) {
    $.ajax({
      url: `/lokasi/${currentLocationId}`,
      type: 'PUT',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        nama: nama,
        deskripsi: deskripsi
      },
      success: function(data) {
        Swal.fire({
          icon: 'success',
          title: 'Lokasi Diperbarui!',
          text: data.message,
          confirmButtonText: 'OK'
        }).then(function() {
          location.reload();
        });
      }
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: 'Nama Lokasi harus diisi!',
      confirmButtonText: 'OK'
    });
  }
});

$('#saveAddBtn').click(function() {
  var nama = $('#addNama').val();
  var deskripsi = $('#addDeskripsi').val();
  var lat = $('#addLatitude').val();
  var lng = $('#addLongitude').val();

  if (nama) {
  $.post('/lokasi', {
    _token: $('meta[name="csrf-token"]').attr('content'),
    nama: nama,
    deskripsi: deskripsi,
    latitude: lat,
    longitude: lng
  }, function(data) {
      Swal.fire({
        icon: 'success',
        title: 'Lokasi Ditambahkan!',
        text: data.message,
        confirmButtonText: 'OK'
      }).then(function() {
        location.reload();
      });
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: 'Nama Lokasi harus diisi!',
      confirmButtonText: 'OK'
    });
  }
});


// Fungsi hapus lokasi dengan konfirmasi modal
function hapusLokasi(id) {
  currentLocationId = id;
  $('#deleteModal').modal('show');
}

// Konfirmasi hapus
$('#confirmDeleteBtn').click(function() {
  $.ajax({
    url: `/lokasi/${currentLocationId}`,
    type: 'DELETE',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function(data) {
      Swal.fire({
        icon: 'success',
        title: 'Lokasi Dihapus!',
        text: data.message,
        confirmButtonText: 'OK'
      }).then(function() {
        location.reload();
      });
    }
  });
});

// Pencarian lokasi
function cariLokasi() {
  var searchQuery = $('#search').val().toLowerCase().trim();

  if (searchQuery === '') {
    Swal.fire({
      icon: 'warning',
      title: 'Masukkan kata kunci!',
      text: 'Ketikkan nama lokasi yang ingin dicari.',
      confirmButtonText: 'OK'
    });
    return;
  }

  var filteredLocations = locations.filter(function(lokasi) {
    return lokasi.nama.toLowerCase().includes(searchQuery);
  });

  if (filteredLocations.length > 0) {
    loadMarkers(filteredLocations);
    // Fokus ke lokasi pertama hasil pencarian
    var firstLocation = filteredLocations[0];
    map.setView([firstLocation.latitude, firstLocation.longitude], 17);

    Swal.fire({
      icon: 'success',
      title: 'Ditemukan!',
      text: `${filteredLocations.length} lokasi cocok ditemukan.`,
      timer: 2500,
      showConfirmButton: false
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Tidak Ditemukan!',
      text: 'Lokasi dengan nama tersebut tidak tersedia.',
      confirmButtonText: 'OK'
    });
  }
}

// Fungsi menemukan lokasi saya
var userMarker, accuracyCircle;

function temukanSaya() {
  if (navigator.geolocation) {
    Swal.fire({
      title: 'Mencari lokasi Anda...',
      text: 'Harap tunggu beberapa detik.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    navigator.geolocation.getCurrentPosition(function(position) {
      Swal.close();
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;
      const accuracy = position.coords.accuracy;

      // Hapus marker sebelumnya jika ada
      if (userMarker) map.removeLayer(userMarker);
      if (accuracyCircle) map.removeLayer(accuracyCircle);

      // Tambahkan marker lokasi user
      userMarker = L.marker([lat, lng], {
        icon: L.icon({
          iconUrl: 'https://cdn-icons-png.flaticon.com/512/64/64113.png',
          iconSize: [30, 30],
          iconAnchor: [15, 30],
          popupAnchor: [0, -30],
        })
      }).addTo(map)
      .bindPopup(`
        <div style="text-align:center;">
          <b>Lokasi Saat Ini</b><br/>
          Lokasi tidak akurat
        </div>
      `).openPopup();

      // Tambahkan lingkaran akurasi
      accuracyCircle = L.circle([lat, lng], {
        color: '#007bff',
        fillColor: '#007bff',
        fillOpacity: 0.2,
        radius: 200,
      }).addTo(map);

      // Fokus ke lokasi user
      map.setView([lat, lng], 17);

    }, function(error) {
      Swal.close();
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: error.message
      });
    }, {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 0
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Geolocation Tidak Didukung',
      text: 'Browser Anda tidak mendukung fitur lokasi.'
    });
  }
}


</script>
</body>
</html>
@endsection
