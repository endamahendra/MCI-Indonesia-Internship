@extends('layout.frontend.app')
@section('css')
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <style>

            body {
                background: #ffffff;
                font-family: "Open Sans", sans-serif;
            }
            h2 {
                color: #000;
                font-size: 26px;
                font-weight: 300;
                text-align: center;
                text-transform: uppercase;
                position: relative;
                margin: 30px 0 60px;
            }
            h2::after {
                content: "";
                width: 100px;
                position: absolute;
                margin: 0 auto;
                height: 4px;
                border-radius: 1px;
                background: #7ac400;
                left: 0;
                right: 0;
                bottom: -20px;
            }
            .carousel {
                margin: 50px auto;
                padding: 0 70px;
            }
            .carousel .item {
                color: #747d89;
                min-height: 325px;
                text-align: center;
                overflow: hidden;
            }
            .carousel .thumb-wrapper {
                padding: 25px 15px;
                background: #fff;
                border-radius: 6px;
                text-align: center;
                position: relative;
                box-shadow: 0 2px 3px rgba(0,0,0,0.2);
            }
            .carousel .item .img-box {
                height: 120px;
                margin-bottom: 20px;
                width: 100%;
                position: relative;
            }
            .carousel .item img {
                max-width: 100%;
                max-height: 100%;
                display: inline-block;
                position: absolute;
                bottom: 0;
                margin: 0 auto;
                left: 0;
                right: 0;
            }
            .carousel .item h4 {
                font-size: 18px;
            }
            .carousel .item h4, .carousel .item p, .carousel .item ul {
                margin-bottom: 5px;
            }
            .carousel .thumb-content .btn {
                color: #7ac400;
                font-size: 11px;
                text-transform: uppercase;
                font-weight: bold;
                background: none;
                border: 1px solid #7ac400;
                padding: 6px 14px;
                margin-top: 5px;
                line-height: 16px;
                border-radius: 20px;
            }
            .carousel .thumb-content .btn:hover, .carousel .thumb-content .btn:focus {
                color: #fff;
                background: #7ac400;
                box-shadow: none;
            }
            .carousel .thumb-content .btn i {
                font-size: 14px;
                font-weight: bold;
                margin-left: 5px;
            }
            .carousel .item-price {
                font-size: 13px;
                padding: 2px 0;
            }
            .carousel .item-price strike {
                opacity: 0.7;
                margin-right: 5px;
            }
            .carousel-control-prev, .carousel-control-next {
                height: 44px;
                width: 40px;
                background: #7ac400;
                margin: auto 0;
                border-radius: 4px;
                opacity: 0.8;
            }
            .carousel-control-prev:hover, .carousel-control-next:hover {
                background: #78bf00;
                opacity: 1;
            }
            .carousel-control-prev i, .carousel-control-next i {
                font-size: 36px;
                position: absolute;
                top: 50%;
                display: inline-block;
                margin: -19px 0 0 0;
                z-index: 5;
                left: 0;
                right: 0;
                color: #fff;
                text-shadow: none;
                font-weight: bold;
            }
            .carousel-control-prev i {
                margin-left: -2px;
            }
            .carousel-control-next i {
                margin-right: -4px;
            }
            .carousel-indicators {
                bottom: -50px;
            }
            .carousel-indicators li, .carousel-indicators li.active {
                width: 10px;
                height: 10px;
                margin: 4px;
                border-radius: 50%;
                border: none;
            }
            .carousel-indicators li {
                background: rgba(0, 0, 0, 0.2);
            }
            .carousel-indicators li.active {
                background: rgba(0, 0, 0, 0.6);
            }
            .carousel .wish-icon {
                position: absolute;
                right: 10px;
                top: 10px;
                z-index: 99;
                cursor: pointer;
                font-size: 16px;
                color: #abb0b8;
            }
            .star-rating li {
                padding: 0;
            }
            .star-rating i {
                font-size: 14px;
                color: #ffc000;
            }

            .custom-btn {
            color: #7ac400;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            background: none;
            border: 1px solid #7ac400;
            padding: 6px 14px;
            margin-top: 5px;
            line-height: 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
            }

        .custom-btn:hover {
            color: #fff;
            background: 7ac400;
            box-shadow: none;
            }
    </style>
@endsection
@section('content')
<!-- Start Banner Hero -->
<div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators mb-5">
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="{{ asset('frontend/assets/img/banner_img_02.png') }}" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left align-self-center">
                            <h1 class="h1">Butuh Alasan untuk Mencintai <b>MCI?</b></h1>
                            <h3 class="h2">Memberi Bukti, Bukan Janji</h3>
                            <p>
                                MCI dikenal sebagai bisnis online bergengsi, bukan hanya karena Keuntungan Fantastisnya tetapi juga Program Tur Gratis yang telah berhasil mengirim ribuan anggotanya ke berbagai destinasi wisata populer di dunia.
                            </p>
                            <form action="{{ route('register') }}" method="GET">
                                <button type="submit" class="btn btn-primary btn-lg custom-btn" style="width: 150px; height: 60px; font-size: 15px;">Mulai</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="{{ asset('frontend/assets/img/banner_img_02.png') }}" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1"><b>MCI</b> - Milliarder Club Indonesia</h1>
                            <h3 class="h2">Membantu Anda Meraih Mimpi</h3>
                            <p>
                                MCI membantu ribuan anggotanya untuk beralih dari paradigma penghasilan bulanan menjadi penghasilan tiga kali lipat dalam sehari. Latar belakang beragam dari pemimpin-pemimpin sukses sekali lagi membuktikan bahwa bisnis ini adalah untuk semua orang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="{{ asset('frontend/assets/img/banner_img_02.png') }}" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1">Visi <b>MCI</b> untuk Masa Depan</h1>
                            <h3 class="h2">Inovasi dan Kepemimpinan</h3>
                            <p>
                                Program Tur Gratis ini berhasil mengirim ribuan anggotanya ke berbagai destinasi wisata populer di dunia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Banner Hero -->
<div class="px-5">
    <div class="row">
        <div class="col-md-12">
            <h2>Produk <b>Terlaris</b></h2>
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="0">
                <!-- Carousel indicators -->
                <ol class="carousel-indicators">
                    @foreach ($products->chunk(4) as $key => $productChunk)
                        <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>

                <div class="carousel-inner">
                    @foreach ($products->chunk(4) as $key => $productChunk)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <div class="row">
                                @foreach ($productChunk as $product)
                                    <div class="col-sm-3">
                                        <div class="card h-100">
                                            <div class="img-box">
                                                <img src="{{ asset($product->photo) }}" class="img-fluid" alt="{{ $product->deskripsi }}" style="max-width: 100%; max-height: 100%;">
                                            </div>
                                            <div class="card-body d-flex flex-column h-100">
                                                <h4 class="card-title">{{ $product->sku }}</h4>
                                                @if ($product->diskon > 0)
                                                    <h4>{{ $product->diskon }} %</h4>
                                                @endif
                                                <div class="star-rating">
                                                    <ul class="list-inline">
                                                        <!-- Tambahkan bintang sesuai dengan rating produk jika ada -->
                                                    </ul>
                                                </div>
                                                <!-- Tampilkan harga dan diskon jika ada -->
                                                @if ($product->diskon > 0)
                                                    @php
                                                        $harga = number_format($product->harga, 0, ',', '.');
                                                        $harga_diskon = number_format($product->harga - ($product->diskon/100 * $product->harga), 0, ',', '.');
                                                    @endphp
                                                    <p class="item-price"><strike>Rp {{ $harga }}</strike><br> <b>Rp {{ $harga_diskon }}</b></p>
                                                @else
                                                    @php
                                                        $harga = number_format($product->harga, 0, ',', '.');
                                                    @endphp
                                                    <p class="item-price"><b>Rp {{ $harga }}</b></p>
                                                @endif
                                                <!-- Tampilkan jumlah terjual -->
                                                <p>Jumlah terjual: {{ $product->sold_quantity }}</p>

                                                <div class="mt-auto text-center">
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="#" class="btn btn-primary add-to-cart custom-btn" data-id="{{ $product->id }}" data-deskripsi="{{ $product->deskripsi }}" data-harga="{{ $product->harga }}" data-diskon="{{ $product->diskon }}" data-sku="{{ $product->sku }}" data-photo="{{ $product->photo }}">
                                                                <i class="fas fa-cart-plus"></i>
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <a href="{{ route('product.detail', ['sku' => $product->sku]) }}" class="btn btn-primary custom-btn">
                                                                <i class="fas fa-info-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Carousel controls -->
                <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>




<div class="bg-light">
    <div class="container py-5">
        <h2>Kategori <b>Terlaris</b></h2>
        <div class="row row-cols-1 row-cols-md-4 g-4" style="padding: 0 70px";>
            @foreach($categories as $category)
                <div class="col">
                    <div class="card h-100">
                    <div class="img-box" style="height: 200px; ; width: 100%; position: relative;">
                        <img src="{{ isset($categoryPhotos[$category->id]) ? asset($categoryPhotos[$category->id]) : asset('images/placeholder.jpg') }}" class="card-img-top" alt="{{ $category->nama_kategori }}" style="max-width: 100%; max-height: 100%; display: inline-block; position: absolute; bottom: 0; margin: 0 auto; left: 0;      right: 0;">
                    </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $category->nama_kategori }}</h5>
                            <a href="{{ route('product.category', ['deskripsi' => $category->deskripsi]) }}" class="btn btn-primary custom-btn">Lihat Produk</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="">
    <div class="container py-5">
        <h2>Penawaran <b>Terbaik</b></h2>
        <div class="row row-cols-1 row-cols-md-4 g-4" style="padding: 0 70px;">
            @foreach ($penawaranTerbaik as $product)
                <div class="col">
                    <div class="card h-100">
                        <div class="img-box">
                            <img src="{{ asset($product->photo) }}" class="img-fluid" alt="{{ $product->deskripsi }}" style="max-width: 100%; max-height: 100%;">
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h4 class="card-title">{{ $product->sku }}</h4>
                            @if ($product->diskon > 0)
                                <h4>{{ $product->diskon }} %</h4>
                            @endif
                            <!-- Tampilkan harga dan diskon jika ada -->
                            @if ($product->diskon > 0)
                                @php
                                    $harga = number_format($product->harga, 0, ',', '.');
                                    $harga_diskon = number_format($product->harga - ($product->diskon/100 * $product->harga), 0, ',', '.');
                                @endphp
                                <p class="item-price"><strike>Rp {{ $harga }}</strike><br> <b>Rp {{ $harga_diskon }}</b></p>
                            @else
                                @php
                                    $harga = number_format($product->harga, 0, ',', '.');
                                @endphp
                                <p class="item-price"><b>Rp {{ $harga }}</b></p>
                            @endif
                            <div class="mt-auto text-center">
                                <div class="row">
                                                        <div class="col">
                                                            <a href="#" class="btn btn-primary add-to-cart custom-btn" data-id="{{ $product->id }}" data-deskripsi="{{ $product->deskripsi }}" data-harga="{{ $product->harga }}" data-diskon="{{ $product->diskon }}" data-sku="{{ $product->sku }}" data-photo="{{ $product->photo }}">
                                                                <i class="fas fa-cart-plus"></i>
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <a href="{{ route('product.detail', ['sku' => $product->sku]) }}" class="btn btn-primary custom-btn">
                                                                <i class="fas fa-info-circle"></i>
                                                            </a>
                                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="bg-light">
    <div class="row mt-5">
        <div class="col-md-12 mt-5">
            <h2>Kenapa harus <b>MCI?</b></h2>
            <div class="row carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="container my-5">
                                <div class="row ">
                                    <div class="col-md-3 mb-4">
                                        <img src="{{ asset('frontend/assets/img/dollar.png') }}" class="bg-primary p-3 rounded-circle mx-auto d-block" width="96" height="96" alt="">
                                        <p class="text-center mt-3">MCI membantu ribuan anggotanya untuk melepaskan diri dari paradigma pendapatan bulanan untuk mendapatkan 3X lipat dalam sehari. Latar belakang beragam pemimpin sukses sekali lagi membuktikan bahwa bisnis ini untuk semua kalangan.</p>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <img src="{{ asset('frontend/assets/img/globe.png') }}" class="bg-primary p-3 rounded-circle mx-auto d-block" width="96" height="96" alt="">
                                        <p class="text-center mt-3">Program Tur Gratis yang telah berhasil mengirim ribuan anggotanya ke berbagai destinasi wisata populer di dunia.</p>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <img src="{{ asset('frontend/assets/img/medal.png') }}" class="bg-primary p-3 rounded-circle mx-auto d-block" width="96" height="96" alt="">
                                        <p class="text-center mt-3">MCI adalah Museum Rekor Dunia Sertifikat Penghargaan dan beberapa penghargaan lainnya. Lihat lebih banyak.</p>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <img src="{{ asset('frontend/assets/img/users.png') }}" class="bg-primary p-3 rounded-circle mx-auto d-block" width="96" height="96" alt="">
                                        <p class="text-center mt-3">MCI dikenal luas sebagai bisnis online bergengsi. Ayo bergabung dengan keluarga besar kami.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="">
    <div class="row mt-5">
        <div class="col-md-12">
            <h2>Tentang <b>Kami</b></h2>
            <div class="row carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="container">
                                <div class="row p-5">
                                    <div class="mx-auto col-md-8 col-lg-6">
                                        <img class="img-fluid" src="{{ asset('frontend/assets/img/about.png') }}" alt="">
                                    </div>
                                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                                        <div class="container-fluid">
                                            <h1 class="h1 text"><b>Tentang MCI</b></h1>
                                            <p>
                                                MC-Indonesia adalah sebuah klub yang dibentuk untuk anggotanya, di mana mereka tidak hanya menikmati kesehatan fisik tetapi juga kesehatan keuangan. Dengan bergabung di MC-Indonesia, seluruh anggota mendapatkan keistimewaan dan produk eksklusif dari kami, termasuk bergabung dalam komunitas jutawan di Indonesia. Dengan memanfaatkan komunitas ini, kami berharap akan terjalin kerjasama yang lebih baik di antara anggotanya dan tentunya membantu meningkatkan kondisi ekonomi di Indonesia. Pernyataan MCI-Indonesia menyatakan bahwa anggotanya dapat mendapatkan bonus, hadiah, komisi, atau manfaat dari setiap orang atau entitas yang berpartisipasi dalam menjadi anggota. MC-Indonesia berhubungan langsung dengan upaya dan jaminan atas hadiah, bonus, komisi, atau keuntungan dalam potensi perdagangan. Hasil produk dapat berbeda-beda antara satu orang dengan orang lainnya.
                                            </p>
                                            <a class="btn btn-primary custom-btn" href="{{ route('about') }}">Lihat Selengkapnya...</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<div class="bg-light">
    <div class="row mt-5">
        <div class="col-md-12">
            <h2><b>Lokasi</b></h2>
            <div class="row carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 mb-0 d-flex align-items-center">
                                    <!-- Embed Google Maps di sini -->
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d32494669.22616801!2d69.89153709999997!3d-6.17966279999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f7f90028b2ed%3A0xc65abef6d4a69763!2sMCI%20Millionaire%20Club%20Indonesia!5e0!3m2!1sid!2sid!4v1720020251208!5m2!1sid!2sid" style="border:0; width: 100%; height: 450px;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
