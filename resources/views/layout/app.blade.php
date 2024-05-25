<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="icon" href="{{ asset('assets/img/mci-logo.png') }}" type="image/png">
  <title>MCI - Millionaire Club Indonesia</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">
      {{-- Loads Roboto --}}
    {{-- @googlefonts --}}

  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/app/tombol.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/app/datatables.min.css')}}" rel="stylesheet">
<script src="{{asset('assets/css/app/datatables.min.js')}}"></script>
<script src="{{asset('assets/css/app/jquery-ui.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script> --}}
@yield('css')

  <!-- =======================================================
    * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  {{-- @include('layouts.modalhapus') --}}

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="/" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/mci-logo.png') }}" alt="" style="width: auto; height: auto; max-width: 30%; max-height: 30%;">
        <span class="d-none d-lg-block" style="font-size: 1.4rem; color: #0062B1;">
            <span style="color: #DEA455; font-size: 1.4rem;">MCI</span> INDONESIA
        </span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            {{-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> --}}
             <span class="d-none d-md-block dropdown-toggle ps-2">
                 @auth
                    Hallo, {{ Auth::user()->name }}
                @endauth</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li>
              <form method="POST" action="{{ route('logout') }}">
                            @csrf
              <a class="dropdown-item d-flex align-items-center" href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                <span>Logout</span>
              </a>
               </form>
            </li>
          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
    </nav>
</header>
@include('layout.nav')


  <!-- ======= Hero Section ======= -->
  <main id="main" class="main">




      <div class="row">

@yield('content')

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright"> <strong><span>© 2021 - Millionaire Club Indonesia.  All Rights Reserved</span></strong>
    </div>
    <div class="credits">
     Versi 1.0.0</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/main.js')}}"></script>
  <script>
function formatRupiah(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // Tambahkan titik jika angka memiliki ribuan
    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    // Tambahkan koma dan satuan mata uang
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? 'Rp ' + rupiah : '';
}
  </script>
@yield('js')
</body>

</html>
