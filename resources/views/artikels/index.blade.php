@extends('layout.app')
@section('css')
<style>
.select2-container {
    width: 100% !important;
        z-index: 10000;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('content')
    @include('artikels.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Master Data</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Artikel</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Artikel</h6>
                    <div style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-primary" onclick="clearForm(); $('#artikelFormModal').modal('show');">
                            <i class="bi-plus-circle me-2"></i>Tambah Data
                        </button>
                    </div>
                    <table class="table table-striped" id="tableArtikel">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Foto</th>
                                <th>Author</th>
                                <th>Kategori</th>
                                <th>Tanggal Publish</th>
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

    @include('artikels.script')
@endsection
