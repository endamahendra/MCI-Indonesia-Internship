<script>
    // Fungsi untuk memformat angka menjadi format rupiah
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Menambahkan titik jika yang diinputkan sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    // Fungsi untuk menampilkan data
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#tableTravelPackage').DataTable({
            ajax: {
                url: '/reward/datatables',
                type: 'GET',
                serverSide: true,
                processing: true,
                dataSrc: 'data'
            },
            columns: [
                { data: 'user.name' },
                { data: 'travel_package.deskripsi' },
                {
                    data: 'travel_package.target',
                    render: function(data) {
                        // Ubah angka menjadi format rupiah
                        return formatRupiah(data, 'Rp ');
                    }
                },
                {
                    data: 'totalBelanja',
                    render: function(data) {
                        // Ubah angka menjadi format rupiah
                        return formatRupiah(data, 'Rp ');
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                    return moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                // Uncomment jika ingin menambahkan tombol edit dan hapus
                // {
                //     data: null,
                //     render: function(data, type, row) {
                //         return '<i class="fa-solid fa-pen-to-square" onclick="editTravelPackage(' + row.id + ')"></i> ' +
                //             '<span style="margin-right: 10px;"></span>' +
                //             '<i class="fa-solid fa-trash" onclick="deleteTravelPackage(' + row.id + ')"></i>';
                //     }
                // }
            ],
            order: [[0, 'asc']]
        });
    });

    // Fungsi untuk menghapus isi form yang sudah diisi
    function clearForm() {
        $('#id').val('');
        $('#deskripsi').val('');
        $('#tanggal').val('');
        $('#target').val('');
        $('#photo').val('');
    }


</script>
