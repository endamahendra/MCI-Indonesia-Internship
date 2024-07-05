<!DOCTYPE html>
<html lang="en">

<head>
    <title>MCI Indonesia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="{{ asset('frontend') }}/assets/img/apple-icon.png">
    <link rel="icon" href="{{ asset('assets/img/mci-logo.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/templatemo.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/fontawesome.min.css">

    @yield('css')
    @if(auth()->check())
        <script>
            var isLoggedIn = true;
            var userId = {{ auth()->id() }};
            var cart = JSON.parse(localStorage.getItem('cart'));
            if (cart && cart.length > 0) {
                var user_id = cart[0].user_id;
                
            } else {
                
            }
        </script>
    @else
        <script>
            var isLoggedIn = false;
        </script>
    @endif
</head>

<body>
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:info@company.com">info@mci-world.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="https://wa.me/6281958898182" target="_blank">0819 5889 8182</a>
                </div>
                <div>
                    <a class="text-light" href="https://www.facebook.com/MCIWorld.id/?locale=id_ID" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/mciofficial/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://x.com/indonesiamci?lang=en" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->

    @include('layout.frontend.nav')

    @yield('content')

    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Keranjang Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cart"></div>
            </div>
        </div>
    </div>

    <!-- Start Script -->
    <script src="{{ asset('frontend') }}/assets/js/jquery-1.11.0.min.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/templatemo.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')

    @include('layout.frontend.footer')
</body>

</html>
