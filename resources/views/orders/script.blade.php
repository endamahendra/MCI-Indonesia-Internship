<script>
    $(document).ready(function() {
        $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/orders/datatables', // Ganti dengan URL ke route Anda
            columns: [
                { data: 'id' },
                { data: 'user.name' },
                {
                    data: 'products',
                    render: function (data, type, row) {
                        var products = data.map(function(product) {
                            return product.deskripsi;
                        });
                        return products.join('<br>'); // Gabungkan nama produk menjadi satu string
                    }
                },
                {
                    data: 'products',
                    render: function (data, type, row) {
                        var hargasatuan = data.map(function(product) {
                            return 'Rp ' + formatRupiah(product.harga);
                        });
                        return hargasatuan.join('<br>'); // Gabungkan harga satuan menjadi satu string
                    }
                },
                {
                    data: 'products',
                    render: function (data, type, row) {
                        var qty = data.map(function(product) {
                            return product.pivot.quantity;
                        });
                        return qty.join('<br>'); // Gabungkan jumlah menjadi satu string
                    }
                },
                {
                    data: 'products',
                    render: function(data, type, row) {
                        var totalHarga = data.reduce(function(acc, product) {
                            return acc + product.pivot.total_harga;
                        }, 0);
                        return 'Rp ' + formatRupiah(totalHarga); // Tampilkan total harga dengan format Rupiah
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data === 'selesai') {
                        return 'Selesai';
                        } else {
                        return `
                            <div>
                                <div class="dropdown"> 
                                <span>${data}</span>
                                <i class="fa fa-pencil" onclick="toggleDropdown(${row.id})" style="cursor:pointer;"></i>
                                <div id="dropdown-${row.id}" class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                                    <a class="dropdown-item" onclick="updateOrderStatus(${row.id}, 'diproses')">Proses</a>
                                    <a class="dropdown-item" onclick="updateOrderStatus(${row.id}, 'selesai')">Selesai</a>
                                    <a class="dropdown-item" onclick="updateOrderStatus(${row.id}, 'batal')">Batalkan</a>
                                </div>
                            </div>
                        `;
                    }
                }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<i class="fa-solid fa-eye" onclick="showOrderDetails(' + row.id + ')"></i> ';
                    }
                }
            ]
        });
    });

    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join('');
        var ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return ribuan;
    }

    function showOrderDetails(orderId) {
        window.location.href = '/orders/' + orderId;
    }

    function toggleDropdown(orderId) {
        var dropdown = document.getElementById(`dropdown-${orderId}`);
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

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
                    url: `/orders/${orderId}/status`,
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
                        $('#orders-table').DataTable().ajax.reload(); // Reload tabel data
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
