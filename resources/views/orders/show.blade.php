@extends('layout.app')
@section('css')
<style>
    @media print {
        /* Semua konten ke mode landscape */
        @page {
            size: landscape;
            margin: 0;
        }

        /* Semua konten akan disembunyikan kecuali yang memiliki kelas .print-section */
        body * {
            visibility: hidden;
        }
        .print-section, .print-section * {
            visibility: visible;
        }

        /* Tombol cetak akan disembunyikan saat mencetak */
        .print-button, .unduh-button {
            display: none;
        }

        /* Hanya kartu yang akan disembunyikan saat mencetak */
        .card {
            visibility: hidden;
        }
    }
</style>

@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12">
                <h3>Detail Order</h3>
            </div>
        </div>
    </div>
</div>
<section class="section print-section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informasi Pesanan</h5>
            <ul>
                <li><strong>ID Pesanan:</strong> {{ $order->id }}</li>
                <li><strong>Nama Pengguna:</strong> {{ $order->user->name }}</li>
                <li><strong>Tanggal Pesanan:</strong> {{ $order->created_at }}</li>
                <li><strong>Status:</strong> {{ $order->status }}</li>
            </ul>
            <h5 class="card-title">Detail Produk</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Harga/satuan</th>
                        <th>Quantity</th>
                        <th>Total Harga (-diskon)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalHarga = 0;
                        $totalDiskon = 0;
                    @endphp
                    @foreach ($order->products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        @php
                            $subtotal = $product->pivot->total_harga;
                            $totalHarga += $subtotal;
                            $subdiskon = ($product->pivot->diskon / 100) * $product->harga;
                            $totalDiskon += $subdiskon;
                        @endphp
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>Diskon</strong></td>
                        <td><strong>Rp {{ number_format($totalDiskon, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Total Harga</strong></td>
                        <td><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary float-end print-button" onclick="printOrder()">Cetak</button>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
function printOrder() {
    window.print();
}
</script>
@endsection
