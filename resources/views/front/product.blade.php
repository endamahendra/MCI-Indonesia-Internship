@extends('layout.frontend.app')
@section('content')

<!-- Start Content -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            @if(session('success'))
        <div class="alert alert-success mt-3">
            {!! session('success') !!}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
            <div class="row mb-4">
                <div class="col-md-8">
                    <form action="{{ route('fproduct.search') }}" id="searchForm" class="d-flex">
                        <input type="text" class="form-control mr-2" name="keyword" id="keyword" placeholder="Kata kunci">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <form id="filterForm" class="d-flex justify-content-end">
                        <select class="form-control mr-2" id="filter">
                            <option value="">Filter</option>
                            <option value="price_asc">Berdasarkan harga terendah</option>
                            <option value="price_desc">Berdasarkan harga tertinggi</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="row" id="product-list">
                @foreach ($products as $d)
                <div class="col-md-3 mb-4">
                    <div class="card product-wap rounded-0 h-100">
                        <div class="card rounded-0">
                            <img class="card-img rounded-0" src="{{ asset('' . $d->photo) }}" width="300" height="200">
                            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li><a class="btn btn-success text-white mt-2" href="{{ route('product.detail', ['sku' => $d->sku]) }}"><i class="far fa-eye"></i></a></li>
                                    <li><a class="btn btn-success text-white mt-2 add-to-cart" data-id="{{ $d->id }}" data-user="{{ Auth::check() ? Auth::user()->id : 'guest' }}"   data-deskripsi="{{ $d->deskripsi }}" data-sku="{{ $d->sku }}" data-harga="{{ $d->harga }}" data-photo="{{ $d->photo }}" data-diskon="{{ $d->diskon }}"><i class="fas fa-cart-plus"></i></a></li>
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
                                <ul class="list-unstyled d-flex mb-1">
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
                            @endif
                            @if (!empty($d->diskon))
                            @php
                                $harga = number_format($d->harga, 0, ',', '.');
                                $harga_diskon = number_format($d->harga - ($d->diskon/100 * $d->harga), 0, ',', '.');
                            @endphp
                                <p class="item-price"><strike>Rp {{ $harga }}</strike><b>    Rp {{ $harga_diskon }}</b></p>
                            @else
                            <p class="mb-0">Harga: {{ 'Rp ' . number_format($d->harga, 0, ',', '.') }}</p>
                            @endif
                            @if (!empty($d->diskon))
                                <p class="mb-0">Diskon: {{ $d->diskon }}%</p>
                            @else
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
@php
    $currentPage = $products->currentPage();
    $keyword = request()->get('keyword');
    $orderBy = request()->get('orderBy');
@endphp

<div class="container mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Previous Page Link -->
            @if ($products->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            <!-- First Page Link -->
            @if ($products->currentPage() > 3)
                <li class="page-item{{ $products->currentPage() == 1 ? ' active' : '' }}">
                    <a class="page-link" href="{{ $products->appends(['keyword' => $keyword])->url(1) }}">1</a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @php
                $maxPagesToShow = 5; // Jumlah maksimum tautan halaman yang ingin ditampilkan
                $halfTotalLinks = floor($maxPagesToShow / 2);
                $startNumber = $products->currentPage() - $halfTotalLinks;
                if ($startNumber < 1) {
                    $startNumber = 1;
                }
                $endNumber = $products->currentPage() + $halfTotalLinks;
                if ($endNumber > $products->lastPage()) {
                    $endNumber = $products->lastPage();
                }
            @endphp

            @for ($i = $startNumber; $i <= $endNumber; $i++)
                <li class="page-item{{ $products->currentPage() == $i ? ' active' : '' }}">
                    <a class="page-link" href="{{ $products->appends(['keyword' => $keyword])->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Last Page Link -->
            @if ($products->currentPage() < $products->lastPage() - 2)
                <li class="page-item{{ $products->currentPage() == $products->lastPage() ? ' active' : '' }}">
                    <a class="page-link" href="{{ $products->appends(['keyword' => $keyword])->url($products->lastPage()) }}">{{ $products->lastPage() }}</a>
                </li>
            @endif

            <!-- Next Page Link -->
            @if ($products->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $products->appends(['keyword' => $keyword])->nextPageUrl() }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
</div>


    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('filter').addEventListener('change', function() {
            const orderBy = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('orderBy', orderBy);
            window.location.href = url.toString();
        });
    });
</script>

@endsection
