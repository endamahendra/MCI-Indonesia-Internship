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
                data: null,
                render: function (data, type, row) {
                    return '<i class="fa-solid fa-eye" onclick="showOrderDetails(' + row.id + ')"></i> '
                }
            }
            ]
        });
    });

    // Fungsi untuk mengubah angka menjadi format mata uang Rupiah
    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join('');
        var ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return ribuan;
    }

    // Fungsi untuk menampilkan detail pesanan
    function showOrderDetails(orderId) {
        window.location.href = '/orders/' + orderId;
    }
</script>
