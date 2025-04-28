<!--begin::Sidebar-->
<aside class="app-sidebar shadow-lg d-flex flex-column" style="min-height: 100vh; width: 250px; background-color: #343a40; color: #fff;">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand d-flex align-items-center justify-content-center py-3 border-bottom" style="border-color: rgba(255, 255, 255, 0.1);">
        <a href="./index.html" class="d-flex align-items-center text-decoration-none">
            <!-- Icon baru di sini -->
            <i class="fa-solid fa-map fs-4 me-2 text-primary"></i>
            <span class="brand-text fs-5 fw-bold text-white">Tubes Gis</span>
        </a>
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Menu-->
    <div class="sidebar-wrapper flex-grow-1 d-flex flex-column">
        <nav class="mt-4">
            <ul class="nav flex-column">
                <!-- Dropdown Menu -->
                <li class="nav-item">
                    <a href="#menuDropdown" class="nav-link d-flex align-items-center text-white" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menuDropdown">
                        <i class="fa-solid fa-layer-group fs-5 me-3 text-info"></i>
                        <span class="fw-semibold">Menu</span>
                        <i class="fa-solid fa-chevron-right ms-auto text-white"></i>
                    </a>
                    <!-- Dropdown Content -->
                    <div class="collapse" id="menuDropdown">
                        <ul class="nav flex-column ps-4">
                            <li class="nav-item">
                                <a href=" {{ route('wilayah.index') }} " class="nav-link d-flex align-items-center text-white">
                                    <i class="fa-solid fa-map fs-6 me-2 text-warning"></i>
                                    <span>Peta</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lokasi.daftar') }}" class="nav-link d-flex align-items-center text-white">
                                    <i class="fa-solid fa-map-location-dot fs-6 me-2 text-primary"></i>
                                    <span>Daftar Lokasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <!--end::Sidebar Menu-->
</aside>
<!--end::Sidebar-->

<!-- Load FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
