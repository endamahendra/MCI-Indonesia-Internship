@extends('layout.app')

@section('content')
    {{-- @include('reward.modal') --}}
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
                        <li class="breadcrumb-item active" aria-current="page">Data Reward</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">

            <div class="card-body">
            <h5 class="card-title">Data Reward</h5>
                <table class="table table-striped" id="tableTravelPackage">
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Paket Wisata</th>
                            <th>Target</th>
                            <th>Total Belanja</th>
                            <th>Tanggal Achivement</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </section>
    </div>

    @include('reward.script')
@endsection
