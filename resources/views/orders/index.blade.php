@extends('layout.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Data Transaksi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Order</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Data Order</h5>
                <table id="orders-table" class="table">
                    <thead>
                        <tr>
                            <th>Order_ID</th>
                            <th>Pengguna</th>
                            <th>Deskripsi</th>
                            <th>Harga/satuan</th>
                            <th>Quantity</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
        </div>
    </div>
</section>

@include('orders.script')

@endsection
