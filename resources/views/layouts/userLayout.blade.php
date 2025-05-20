<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.2.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2025 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="description" content="Sistem Informasi Tugas Akhir" />
    <meta name="author" content="Perencanaan Wilayah dan Kota ITERA" />
    <meta name="keyword"
        content="Manajemen TA PWK ITERA, Sistem Informasi TA, Pengajuan Judul TA PWK ITERA, Upload Dokumen TA PWK ITERA, Jadwal Seminar Sidang TA PWK, Mahasiswa PWK ITERA, Dosen Pembimbing PWK ITERA, Koordinator TA PWK ITERA" />
    <title>SITA PWK</title>
    <meta name="theme-color" content="#ffffff" />
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ secure_asset('coreui/vendors/simplebar/css/simplebar.css') }}" />
    <link rel="stylesheet" href="{{ secure_asset('coreui/css/vendors/simplebar.css') }}" />
    <!-- Main styles for this application-->
    <link href="{{ secure_asset('coreui/css/style.css') }}" rel="stylesheet" />
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{ secure_asset('coreui/css/examples.css') }}" rel="stylesheet" />
    <script src="{{ secure_asset('coreui/js/config.js') }}"></script>
    <script src="{{ secure_asset('coreui/js/color-modes.js') }}"></script>
    <link href="{{ secure_asset('coreui/vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet" />

    <!-- Datepicker & Datatables-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" />
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    @stack('style')

</head>

<body>
    <div class="sidebar sidebar-light sidebar-fixed border-end" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="sidebar-brand">
                <img src="{{ secure_asset('coreui/assets/brand/logo-pwk.png') }}" alt="CoreUI Logo" class="sidebar-brand-full"
                    width="100%" height="100%" />
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
                onclick='coreui.Sidebar.getInstance(document.querySelector("#sidebar")).toggle()'></button>
        </div>
        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-title fw-semibold text-primary my-0">Fitur Umum</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.home') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-apps') }}">
                        </use>
                    </svg>
                    Homepage</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.periode-info') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-flag-alt') }}">
                        </use>
                    </svg>
                    Informasi Periode</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.periode-topik') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-list-numbered') }}">
                        </use>
                    </svg>
                    List Topik TA</a>
            </li>

            <li class="nav-title fw-semibold text-primary my-0">Manajemen</li>


            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-school') }}"></use>
                    </svg>
                    Tugas Akhir</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.ta-proposal') }}"><span class="nav-icon"><span
                                    class=""></span></span>Pengajuan Proposal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.ta-bimbingan') }}"><span class="nav-icon"><span
                                    class=""></span></span>Dosen Pembimbing</a>
                    </li>
                </ul>
            </li>

            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-book') }}"></use>
                    </svg>
                    Pendaftaran</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.sid-sempro') }}"><span class="nav-icon"><span
                                    class=""></span></span>Seminar Proposal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.sid-semhas') }}"><span class="nav-icon"><span
                                    class=""></span></span>Seminar Pembahasan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.sid-ujian') }}"><span class="nav-icon"><span
                                    class=""></span></span>Sidang Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.sid-nilai') }}"><span class="nav-icon"><span
                                    class=""></span></span>Nilai dan Hasil</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.sched-jadwal') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}">
                        </use>
                    </svg>
                    Jadwal Sidang</a>
            </li>
        </ul>
        <div class="sidebar-footer border-top d-none d-md-flex">
            <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
        <header class="header header-sticky p-0 mb-4">
            <div class="container-fluid border-bottom px-4">
                <button class="header-toggler" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
                    style="margin-inline-start: -14px">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
                    </svg>
                </button>
                <ul class="header-nav d-none d-lg-flex">
                </ul>
                <ul class="header-nav ms-auto"></ul>
                <ul class="header-nav">
                    <li class="nav-item">
                        <span class="nav-link">{{ Auth::user()->email }}</span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <img class="avatar-img"
                                    src="{{ secure_asset('coreui/assets/img/avatars/cat-Freepik.png') }}"
                                    alt="user@email.com" />
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                    <use
                                        xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-user') }}">
                                    </use>
                                </svg>
                                Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <svg class="icon me-2">
                                    <use
                                        xlink:href="{{ secure_asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                                    </use>
                                </svg>
                                Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="container-fluid px-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                        <li class="breadcrumb-item active"><span>@yield('breadcrum-title', 'Dashboard')</span></li>
                    </ol>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        @yield('mainContent')
        <!-- End::Main Content -->

        <footer class="footer px-2">
            <div>
                Sistem Informasi Tugas Akhir &copy
                <a href="https://pwk.itera.ac.id/"> 2025 Perencanaan Wilayah dan
                    Kota ITERA</a>
            </div>
        </footer>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ secure_asset('coreui/vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ secure_asset('coreui/vendors/simplebar/js/simplebar.min.js') }}"></script>
    <script>
        const header = document.querySelector("header.header");

        document.addEventListener("scroll", () => {
            if (header) {
                header.classList.toggle(
                    "shadow-sm",
                    document.documentElement.scrollTop > 0
                );
            }
        });
    </script>

    <!-- Custom Javascript -->
    @stack('script')
</body>

</html>
