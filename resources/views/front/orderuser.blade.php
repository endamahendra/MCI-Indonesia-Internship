@extends('layout.frontend.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app/datatables.min.css')}}">
@include('layout.frontend.csssidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layout.frontend.sidebar')
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="dashboard-content pt-3 pb-2">
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
                <div class="card">
                    <div class="card-body">
                        <h1>Order</h1>
                        <div id="order-status-message-kirim" class="alert alert-info" style="display:none; max-height: 30px; overflow: hidden; display: flex; align-items: center; ">
                            <span style="white-space: normal;">
                                Status pesanan ID <span id="order-ids-kirim"></span> akan otomatis menjadi diterima setelah tanggal <span id="cutoff-dates-kirim"></span>.
                            </span>
                        </div>

                        <div id="order-status-message-terima" class="alert alert-success" style="display:none; max-height: 30px; overflow: hidden; display: flex; align-items: center; ">
                            <span style="white-space: normal;">
                                Status pesanan ID <span id="order-ids-terima"></span> akan otomatis menjadi selesai setelah tanggal <span id="cutoff-dates-terima"></span>.
                            </span>
                        </div>
                        <div class="table-responsive">
                        <table id="orders-table" class="table">
                            <thead>
                                <tr>
                                    <th>Order_ID</th>
                                    <th>Pengguna</th>
                                    <th>Deskripsi</th>
                                    <th>Diskon</th>
                                    <th>Harga/satuan</th>
                                    <th>Quantity</th>
                                    <th>Total Harga (-diskon)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/css/app/datatables.min.js')}}"></script>
<script>
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik jika angka memiliki ribuan
        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        // Tambahkan koma dan satuan mata uang
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? 'Rp ' + rupiah : '';
    }
    $(document).ready(function () {
    $('#orders-table').DataTable({
        ajax: {
            url: '/f/order/datatable',
            dataSrc: function (json) {
                var pendingOrders = json.data.filter(order => order.status === 'Dikirim');
                if (pendingOrders.length > 0) {
                    var orderIds = pendingOrders.map(order => order.id).join(', ');
                    var tanggalPengiriman = pendingOrders.map(order => new Date(order.tanggal_pengiriman));
                    var latestTanggal = new Date(Math.max.apply(null, tanggalPengiriman)).toLocaleDateString('id-ID');
                    var tanggalPengirimanPlus14 = new Date(Math.max.apply(null, tanggalPengiriman));
                    tanggalPengirimanPlus14.setDate(tanggalPengirimanPlus14.getDate() + 14); // Menambah 14 hari
                    var tanggalPengirimanPlus14Formatted = tanggalPengirimanPlus14.toLocaleDateString('id-ID');
                    document.getElementById('order-ids-kirim').textContent = orderIds;
                    document.getElementById('cutoff-dates-kirim').textContent = tanggalPengirimanPlus14Formatted;
                    $('#order-status-message-kirim').show();
                } else {
                    $('#order-status-message-kirim').hide();
                }
                
                var orderDiterima = json.data.filter(order => order.status === 'Diterima');
                if (orderDiterima.length > 0) {
                    var orderIds = orderDiterima.map(order => order.id).join(', ');
                    var tanggalDiterima = orderDiterima.map(order => new Date(order.tanggal_diterima));
                    var latestTanggal = new Date(Math.max.apply(null, tanggalDiterima)).toLocaleDateString('id-ID');
                    var tanggalDiterimaPlus14 = new Date(Math.max.apply(null, tanggalDiterima));
                    tanggalDiterimaPlus14.setDate(tanggalDiterimaPlus14.getDate() + 14); // Menambah 14 hari
                    var tanggalDiterimaPlus14Formatted = tanggalDiterimaPlus14.toLocaleDateString('id-ID');
                    document.getElementById('order-ids-terima').textContent = orderIds;
                    document.getElementById('cutoff-dates-terima').textContent = tanggalDiterimaPlus14Formatted;
                    $('#order-status-message-terima').show();
                } else {
                    $('#order-status-message-terima').hide();
                }
                return json.data;
            },
            error: function (xhr, error, thrown) {
                console.error(`Error fetching data: ${error}`);
            }
        },
            columns: [
                { data: 'id' },
                { data: 'user.name' },
                {
                    data: 'products',
                    render: function (data) {
                        var productList = '<ul>';
                        data.forEach(product => {
                            productList += `<li>${product.sku}</li>`;
                        });
                        productList += '</ul>';
                        return productList;
                    }
                },

                {
                    data: 'products',
                    render: function (data) {
                        return data.map(product => product.pivot.diskon + '%').join('<br>');
                    }
                },
                {
                    data: 'products',
                    render: function (data) {
                        return data.map(product => 'Rp ' + formatRupiah(product.harga)).join('<br>');
                    }
                },
                {
                    data: 'products',
                    render: function (data) {
                        return data.map(product => product.pivot.quantity).join('<br>');
                    }
                },
                {
                    data: 'products',
                    render: function (data) {
                        var totalHarga = data.reduce((acc, product) => acc + parseFloat(product.pivot.total_harga), 0);
                        return 'Rp ' + formatRupiah(totalHarga);
                    }
                },
                {
                    data: 'status',
                    render: function (data, type, row) {
                        if (data === 'Dikirim') {
                            return `
                                <div>
                                    <p>${data}</p>
                                    <p>Resi: ${row.resi}</p>
                                    <p>Jasa Pengiriman: ${row.jasa_kirim}</p>
                                    <a class="btn btn-primary" style="border-radius: 20px;" onclick="updateOrderStatus(${row.id}, 'Diterima')">Terima</a>
                                </div>`;
                        } else if (data === 'Diterima') {
                            return `
                                <div>
                                    <span>${data}</span>
                                    <a class="btn btn-success" onclick="updateOrderStatus(${row.id}, 'Selesai')">Selesai</a>
                                </div>`;
                        } else if (data === 'Selesai') {
                            return `
                                <div>
                                    <span>${data}</span>
                                    <a class="btn btn-warning" onclick="window.location.href='/f/orders/${row.id}/rating'">Rate</a>
                                </div>`;
                        } else {
                            return `<span>${data}</span>`;
                        }
                    }
                },
            ]
        });
    });

    function updateOrderStatus(orderId, status) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Anda akan mengubah status menjadi "${status}".`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Tidak, batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/f/orders/${orderId}/status`,
                    type: 'PUT',
                    data: {
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Berhasil!',
                            'Status telah diperbarui.',
                            'success'
                        );

                        // Jika status adalah 'Selesai', arahkan ke halaman rating
                        if (status === 'Selesai') {
                            window.location.href = `/f/orders/${orderId}/rating`;
                        } else {
                            $('#orders-table').DataTable().ajax.reload(); // Reload tabel data
                        }
                    },
                    error: function(error) {
                        Swal.fire(
                            'Gagal!',
                            'Gagal memperbarui status.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endsection
