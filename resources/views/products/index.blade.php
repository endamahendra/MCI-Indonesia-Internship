@extends('layout.app')
@section('css')
<style>
.select2-container {
    width: 100% !important;
        z-index: 10000;
}
</style>
@endsection
@section('content')
    @include('products.modal')
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Produk</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">

            <div class="card-body">
            <h5 class="card-title">Data Produk</h5>
            <div style="margin-bottom: 10px;">
                <button type="button" class="btn btn-primary" onclick="clearForm(); $('#productFormModal').modal('show');">
                    <i class="bi-plus-circle me-2"></i>Tambah Data
                </button>
            </div>
                <table class="table table-striped" id="tableProduct">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Diskon</th>
                            <th>Kategori</th>
                            <th>Photo</th>
                            <th>Rating</th>
                            <th>Dibuat pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </section>
    </div>

    @include('products.script')
@endsection
