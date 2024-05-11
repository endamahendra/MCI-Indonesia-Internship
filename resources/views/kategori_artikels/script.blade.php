<script>

    //fungsi untuk menampilkan data
    $(document).ready(function() {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $('#tableKategoriArtikel').DataTable({
            // dom: 'Bfrtip',
            // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'],
            ajax: {
                url: '/kategori-artikel/datatables',
                type: 'GET',
                "serverSide": true,
                "processing": true,

            },
            columns: [
                { data: 'nama_kategori' },
                { data: 'deskripsi' },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<i class="fa-solid fa-pen-to-square" onclick="editCategory(' + row.id + ')"></i> ' +
                            '<span style="margin-right: 10px;"></span>' +
                            '<i class="fa-solid fa-trash" onclick="deleteCategory(' + row.id + ')"></i>';
                    }
                }
            ],
            order: [[0, 'asc']]
        });
    });

    //fungsi untuk menyimpan data yang diinput
    function saveKategoriArtikel () {
        var id = $('#id').val();
        var method = (id === '') ? 'POST' : 'PUT';
        var data = {
            nama_kategori: $('#nama_kategori').val(),
            deskripsi: $('#deskripsi').val(),
        };
        $.ajax({
            url: '/kategori-artikel' + (method === 'POST' ? '' : '/' + id),
            type: method,
            data:data,
            success: function (response) {
                Swal.fire({
                    title: 'Sukses',
                    text: 'Data berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearForm();
                        $('#tableKategoriArtikel').DataTable().ajax.reload();
                        $('#kategoriArtikelFormModal').modal('hide');
                    }
                });
            },
            error: function (error) {
            let errorMessage = '';
            const errorData = error.responseJSON.error;
            for (let key in errorData) {
                if (errorData.hasOwnProperty(key)) {
                    errorMessage += errorData[key][0] + '\n';
                }
            }
            Swal.fire({
                title: 'Error',
                text:  'Gagal menyimpan data, periksa kembali inputan anda.\n' + errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

    //edit data product
    function editCategory(id) {
    $.ajax({
        url: '/kategori-artikel/' + id,
        type: 'GET',
        success: function (response) {
            $('#id').val(response.kategori_artikel.id);
            $('#nama_kategori').val(response.kategori_artikel.nama_kategori);
            $('#deskripsi').val(response.kategori_artikel.deskripsi);
            // Mengisi formulir dengan data yang akan diedit
            $('#kategoriArtikelFormModalLabel').text('Form Edit Data Kategori Artikel');
            $('#simpanKategoriArtikel').text('Simpan Perubahan');
            $('#kategoriArtikelFormModal').modal('show');
        },
        error: function (error) {
            Swal.fire({
                    title: 'Error',
                    text: 'Gagal mengambil data Kategori Artikel.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
        }
    });
}


    function deleteCategory(id) {
        // Menampilkan modal konfirmasi penghapusan
        Swal.fire({
            title: 'Konfirmasi Hapus Data',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi penghapusan
                $.ajax({
                    url: '/kategori-artikel/' + id,
                    type: 'DELETE',
                    success: function (response) {
                        // Menampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Data berhasil dihapus',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Memuat ulang data setelah penghapusan
                            $('#tableKategoriArtikel').DataTable().ajax.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        // Menampilkan notifikasi kesalahan
                        if (xhr.status == 404) {
                            Swal.fire({
                                title: 'Peringatan',
                                text: 'Data dengan ID tersebut tidak ditemukan.',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Gagal menghapus data kategori artikel.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        });
    }

    //fungsi untuk menghapus isi form yang sudah diisi
    function clearForm() {
    $('#nama_kategori').val('');
    $('#deskripsi').val('');
    $('#kategoriArtikelFormModalLabel').text('Form Tambah Data Kategori Artikel');
    $('#simpanKategoriArtikel').text('Simpan');
 }
</script>
