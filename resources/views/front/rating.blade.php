@extends('layout.frontend.app')

@section('css')
<style>
    .rating .star {
        font-size: 30px; /* Ukuran bintang */
        cursor: pointer;
        color: #888; /* Warna bintang standar */
        transition: color 0.3s; /* Transisi warna untuk efek hover */
    }

    .rating .star.selected,
    .rating .star.hover {
        color: #f0ad4e; /* Warna bintang yang dipilih atau efek hover */
    }
</style>
@endsection

@section('content')
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
    <h1 class="mb-4 text-center">Beri Rating untuk Produk dalam Pesanan Anda</h1>
    <form action="{{ route('orders.submitRating', $order->id) }}" method="POST">
        @csrf
        @foreach ($order->products as $product)
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="form-group border p-3 rounded mt-4 mb-4">
                    <h3>{{ $product->sku }}</h3>
                    <img src="{{ asset($product->photo) }}" alt="Product Image" class="img-fluid rounded">
                    <input type="hidden" name="ratings[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                    <label for="ratings[{{ $loop->index }}][rating]" class="mt-3">Rating:</label>
                    <div class="rating d-inline-block" data-product-index="{{ $loop->index }}">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" name="ratings[{{ $loop->index }}][rating]" id="rating_{{ $loop->index }}" value="">
                    <br>
                    <label for="ratings[{{ $loop->index }}][review]" class="mt-3">Ulasan:</label>
                    <textarea name="ratings[{{ $loop->index }}][review]" id="review_{{ $loop->index }}" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <button type="submit" class="btn btn-primary mt-3">Kirim</button>
            </div>
        </div>
    </form>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.rating .star').on('click', function() {
            var value = $(this).data('value');
            var index = $(this).closest('.rating').data('product-index');
            $('#rating_' + index).val(value);
            $(this).addClass('selected');
            $(this).prevAll('.star').addClass('selected');
            $(this).nextAll('.star').removeClass('selected');
        });

        // Hover effect for stars
        $('.rating .star').hover(function() {
            $(this).addClass('hover');
            $(this).prevAll('.star').addClass('hover');
        }, function() {
            $(this).removeClass('hover');
            $(this).prevAll('.star').removeClass('hover');
        });
    });
</script>
@endsection
