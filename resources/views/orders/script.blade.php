<script>
    $(document).ready(function() {
        $('#orders-table').DataTable({
                    ajax: {
            url: "{{ route('orders.data') }}",
            type: 'GET',
            "serverSide": true,
            "processing": true,
        },
            columns: [
                { data: 'id' },
                { data: 'user.name' },
                {
                    data: 'products',
                    render: function (data, type, row) {
                        var products = data.map(function(product) {
                            return product.sku;
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
                            return acc + parseFloat(product.pivot.total_harga);
                        }, 0);
                        return 'Rp ' + formatRupiah(totalHarga); // Tampilkan total harga dengan format Rupiah
                    }
                },
                { data: 'tanggal_pengiriman' },
                { data: 'resi' },
                
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
                                    <a class="dropdown-item" style="cursor:pointer;" data-status="Diproses" onclick="updateOrderStatus(${row.id}, 'Diproses')">Proses</a>
                                    <a class="dropdown-item" style="cursor:pointer;" data-status="Dikirim" onclick="updateOrderStatus(${row.id}, 'Dikirim')">Kirim</a>
                                    <a class="dropdown-item" style="cursor:pointer;" data-status="Dibatalkan" onclick="updateOrderStatus(${row.id}, 'Dibatalkan')">Batal</a>
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
            ],
            order: [[0, 'desc']],
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
    let confirmMessage = '';
    switch (status) {
        case 'Diproses':
            confirmMessage = 'Apakah Anda yakin ingin memproses pesanan ini?';
            break;
        case 'Dikirim':
            confirmMessage = 'Apakah Anda yakin ingin mengirim pesanan ini?';
            break;
        case 'Dibatalkan':
            confirmMessage = 'Apakah Anda yakin ingin membatalkan pesanan ini?';
            break;
        default:
            confirmMessage = 'Apakah Anda yakin ingin mengubah status ini?';
            break;
    }

    Swal.fire({
        title: 'Konfirmasi',
        text: confirmMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            if (status === 'Dikirim') {
                // Jika status 'Dikirim', tampilkan modal input nomor resi dan jasa kirim
                showInputResiModal(orderId, status);
            } else {
                // Lanjutkan dengan mengirimkan permintaan PUT
                sendUpdateRequest(orderId, status);
            }
        }
    });
}

function showInputResiModal(orderId, status) {
    if (status === 'Dikirim') {
        Swal.fire({
            title: 'Input Detail Pengiriman',
            html:
                '<input id="input-resi" class="swal2-input" placeholder="Masukkan nomor resi">' +
                '<select id="input-jasa-pengiriman" class="swal2-select">' +
                '<option value="JNE">JNE</option>' +
                '<option value="J&T Express">J&T Express</option>' +
                '<option value="Sicepat">Sicepat</option>' +
                '<option value="POS Indonesia">POS Indonesia</option>' +
                '</select>',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    resi: document.getElementById('input-resi').value,
                    jasa_kirim: document.getElementById('input-jasa-pengiriman').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Lanjutkan dengan mengirimkan permintaan PUT
                sendUpdateRequest(orderId, status, result.value.resi, result.value.jasa_kirim);
            }
        });
    } else {
        sendUpdateRequest(orderId, status);
    }
}


function sendUpdateRequest(orderId, status, resi = null, jasa_kirim = null) {
    let data = {
        status: status,
        _token: '{{ csrf_token() }}'
    };

    if (resi !== null) {
        data.resi = resi;
    }

    if (jasa_kirim !== null) {
        data.jasa_kirim = jasa_kirim;
    }

    $.ajax({
        url: `/orders/${orderId}/status`,
        type: 'PUT',
        data: data,
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
</script>
