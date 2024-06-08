@extends('layout.frontend.app')
@section('content')

<!-- Start Content -->
<div class="container py-5">
    <div class="row">

        <div class="col-lg-3">
            <h1 class="h2 pb-4">Categories</h1>
            <ul class="list-unstyled templatemo-accordion">
                <li class="pb-3">

                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                        Gender
                        <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                    <ul class="collapse show list-unstyled pl-3">
                        <li><a class="text-decoration-none" href="#">Men</a></li>
                        <li><a class="text-decoration-none" href="#">Women</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6 pb-4">
                    <div class="d-flex">
                        <form id="filterForm">
                            <select class="form-control" id="filter">
                                <option value="">Filter</option>
                                <option value="price_asc">Berdasarkan harga terendah</option>
                                <option value="price_desc">Berdasarkan harga Tertinggi</option>
                            </select>
                            <input type="text" class="form-control" id="keyword" placeholder="Kata kunci">
                            <button type="submit" class="btn btn-primary ml-2">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" id="product-list">
                @foreach ($products as $d)
                <div class="col-md-4">
                    <div class="card mb-4 product-wap rounded-0">
                        <div class="card rounded-0">
                            <img class="card-img rounded-0 img-fluid" src="{{ asset('' .$d->photo) }}">
                            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li><a class="btn btn-success text-white" href="shop-single.html"><i class="far fa-heart"></i></a></li>
                                    <li><a class="btn btn-success text-white mt-2" href="shop-single.html"><i class="far fa-eye"></i></a></li>
                                    <li><a class="btn btn-success text-white mt-2" href="shop-single.html"><i class="fas fa-cart-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="shop-single.html" class="h3 text-decoration-none">{{$d->sku}}</a>
                            <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                <li class="pt-2">
                                    <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                    <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                    <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                    <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                    <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                </li>
                            </ul>
                            @if ($d->ratings->isNotEmpty())
                                <ul class="list-unstyled d-flex justify-content-center mb-1">
                                    <li>
                                        @php
                                            $averageRating = $d->ratings->avg('rating');
                                            $maxRating = 5;
                                        @endphp

                                        {{-- Tampilkan bintang penuh --}}
                                        @for ($i = 0; $i < $averageRating; $i++)
                                            <i class="text-warning fa fa-star"></i>
                                        @endfor

                                        {{-- Tampilkan bintang kosong --}}
                                        @for ($i = $averageRating; $i < $maxRating; $i++)
                                            <i class="text-muted fa fa-star"></i>
                                        @endfor
                                    </li>
                                </ul>
                            @else
                                <p>Belum ada rating untuk produk ini.</p>
                            @endif

                            <p class="text-center mb-0">{{ 'Rp ' . number_format($d->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
                <ul class="pagination pagination-lg justify-content-end">
                    <li class="page-item disabled">
                        <a class="page-link active rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="#" tabindex="-1">1</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
<!-- End Content -->

<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var orderBy = document.getElementById('filter').value;
        var keyword = document.getElementById('keyword').value;
        var url = '{{ url("/f/products/search") }}' + '?query=' + keyword + '&orderBy=' + orderBy;

        window.location.href = url;
    });
</script>
=======
                        <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                            Gender
                            <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                        </a>
                        <ul class="collapse show list-unstyled pl-3">
                            <li><a class="text-decoration-none" href="#">Men</a></li>
                            <li><a class="text-decoration-none" href="#">Women</a></li>
                        </ul>
                    </li>
                    <li class="pb-3">
                        <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                            Sale
                            <i class="pull-right fa fa-fw fa-chevron-circle-down mt-1"></i>
                        </a>
                        <ul id="collapseTwo" class="collapse list-unstyled pl-3">
                            <li><a class="text-decoration-none" href="#">Sport</a></li>
                            <li><a class="text-decoration-none" href="#">Luxury</a></li>
                        </ul>
                    </li>
                    <li class="pb-3">
                        <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                            Product
                            <i class="pull-right fa fa-fw fa-chevron-circle-down mt-1"></i>
                        </a>
                        <ul id="collapseThree" class="collapse list-unstyled pl-3">
                            <li><a class="text-decoration-none" href="#">Bag</a></li>
                            <li><a class="text-decoration-none" href="#">Sweather</a></li>
                            <li><a class="text-decoration-none" href="#">Sunglass</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 pb-4">
                        <div class="d-flex">
                            <select class="form-control">
                                <option>Featured</option>
                                <option>A to Z</option>
                                <option>Item</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($products as $d)
                    <div class="col-md-4">
                        <div class="card mb-4 product-wap rounded-0">
                            <div class="card rounded-0">
                                <img class="card-img rounded-0 img-fluid" src="{{ asset('' .$d->photo) }}">
                                <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                    <ul class="list-unstyled">
                                        <li><a class="btn btn-success text-white" href="shop-single.html"><i class="far fa-heart"></i></a></li>
                                        <li><a class="btn btn-success text-white mt-2" href="shop-single.html"><i class="far fa-eye"></i></a></li>
                                        <li><a class="btn btn-success text-white mt-2" href="shop-single.html"><i class="fas fa-cart-plus"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <a href="shop-single.html" class="h3 text-decoration-none">{{$d->sku}}</a>
                                <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                    <li class="pt-2">
                                        <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                    </li>
                                </ul>
                                @if ($d->ratings->isNotEmpty())
            <ul class="list-unstyled d-flex justify-content-center mb-1">
                <li>
                    @php
                        $averageRating = $d->ratings->avg('rating');
                        $maxRating = 5;
                    @endphp

                    {{-- Tampilkan bintang penuh --}}
                    @for ($i = 0; $i < $averageRating; $i++)
                        <i class="text-warning fa fa-star"></i>
                    @endfor

                    {{-- Tampilkan bintang kosong --}}
                    @for ($i = $averageRating; $i < $maxRating; $i++)
                        <i class="text-muted fa fa-star"></i>
                    @endfor
                </li>
            </ul>
        @else
            <p>Belum ada rating untuk produk ini.</p>
        @endif

                                <p class="text-center mb-0">{{ 'Rp ' . number_format($d->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div div="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link active rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="#" tabindex="-1">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0 text-dark" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-0 shadow-sm border-top-0 border-left-0 text-dark" href="#">3</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <!-- End Content -->
@endsection
