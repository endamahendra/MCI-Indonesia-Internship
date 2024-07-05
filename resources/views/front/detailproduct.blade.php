@extends('layout.frontend.app')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row gx-5">
      <aside class="col-lg-6">
        <div class="border rounded-4 mb-3 d-flex justify-content-center">
          <a data-fslightbox="mygalley" class="rounded-4" target="_blank" data-type="image" href="{{ asset($product->photo) }}" class="item">
            <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit" src="{{ asset($product->photo) }}" />
          </a>
        </div>
      </aside>
      <main class="col-lg-6">
        <div class="ps-lg-3">
          <h4 class="title text-dark">
            {{$product->sku}}
          </h4>
          <div class="d-flex flex-row my-3">
            <div class="text-warning mb-1 me-2">
           @php
                                            $averageRating = $product->ratings->avg('rating');
                                            $maxRating = 5;
                                        @endphp

                                        {{-- Tampilkan bintang penuh --}}
                                        @for ($i = 1; $i <= $maxRating; $i++)
                                            @if ($i <= $averageRating)
                                                <i class="text-warning fa fa-star"></i>
                                            @else
                                                <i class="text-muted fa fa-star"></i>
                                            @endif
                                        @endfor
            </div>
          </div>

          <div class="mb-3">
            <span class="h5"></span>
            <span class="text-muted">{{ 'Rp ' . number_format($product->harga, 0, ',', '.') }}/per pcs</span>
          </div>

          <p>
            {{$product->deskripsi}}
          </p>
          <a href="#" class="btn btn-primary shadow-0 add-to-cart" data-id="{{ $product->id }}" data-deskripsi="{{ $product->deskripsi }}" data-sku="{{ $product->sku }}" data-harga="{{ $product->harga }}" data-diskon="{{ $product->diskon }}" data-photo="{{ $product->photo }}" style="color: #7ac400;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            background: none;
            border: 1px solid #7ac400;
            padding: 10px 20px;
            margin-top: 10px;
            line-height: 16px;
            border-radius: 20px;
            transition: all 0.3s ease;"> <i class="me-1 fa fa-shopping-basket"></i> Tambahkan ke Keranjang </a>
        </div>
      </main>
                <div class="card border-0">
                    <div class="card-body">
                        <h3 class="card-title">Rating dan Komentar</h3>
                        @if ($product->ratings->isNotEmpty())
                            <div>
                                <div class="d-flex mb-2">
                                    <h5 class="mr-2">Rating:</h5>
                                    <div class="rating mr-2">
                                        @php
                                            $averageRating = $product->ratings->avg('rating');
                                            $maxRating = 5;
                                        @endphp
                                        @for ($i = 1; $i <= $maxRating; $i++)
                                            @if ($i <= $averageRating)
                                                <i class="text-warning fa fa-star"></i>
                                            @else
                                                <i class="text-muted fa fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span>({{ number_format($averageRating, 1) }}) / 5</span>
                                </div>

                                @foreach ($product->ratings as $rating)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div>
                                                <strong>{{ $rating->user->name }}</strong>
                                                <span class="text-muted">- {{ $rating->created_at->diffForHumans() }}</span><br>
                                                @php
                                                    $stars = $rating->rating;
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $stars)
                                                        <i class="text-warning fa fa-star"></i>
                                                    @else
                                                        <i class="text-muted fa fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="ml-2">({{ $rating->rating }}/5)</span>
                                            </div>
                                            <p>{{ $rating->comment }}</p> <!-- Tampilkan komentar di sini -->
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Belum ada rating untuk produk ini.</p>
                        @endif
                    </div>
                </div>
    </div>
  </div>
</section>
@endsection
