@extends('layout.frontend.app')

@section('content')
<section class="bg-light">
   <div class="container pb-5">
    <div class="row">

    <div class="card mt-5">
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
        <div class="card-header mt-3">
            Rincian Pesanan
        </div>
        <div class="card-body">
            <h5 class="card-title">Nomor Order #{{ $order->id }}</h5>
            <p class="card-text">Pemesan: {{ $order->user->name }}</p>
                        <p class="card-text">
                Total Harga: Rp
                @php
                    $total_order_harga = $order->products->sum(function($product) {
                        return $product->pivot->total_harga;
                    });
                @endphp
                {{ number_format($total_order_harga, 0, ',', '.') }}
            </p>
            <p class="card-text">
                <a href="{{ $order->link }}" target="_blank" class="btn btn-success">Konfirmasi Pesanan</a>
            </p>
            <hr>
            <h5>Rincian Produk:</h5>
            <ul>
                @foreach($order->products as $product)
                    <li>{{ $product->sku }} ({{ $product->pivot->quantity }} x Rp {{ number_format($product->harga, 0, ',', '.') }}) = Rp {{ number_format($product->pivot->total_harga, 0, ',', '.') }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
</div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            localStorage.removeItem('cart');
        @endif
    });
</script>
@endsection
