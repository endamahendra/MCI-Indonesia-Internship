<script>

    // Fungsi untuk menampilkan data
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#tableArtikel').DataTable({
            // dom: 'Bfrtip',
            // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'],
            ajax: {
                url: '/artikel/datatables',
                type: 'GET',
                "serverSide": true,
                "processing": true,
            },
            columns: [
                { data: 'judul' },
                {
                    data: 'photo',
                    render: function(data, type, row) {
                        return '<img src="' + data + '" class="img-thumbnail" style="width: 100px; height: auto;">';
                    }
                },
                { data: 'user.name' },
                {
                    data: 'kategoriartikels',
                    render: function(data, type, row) {
                        var kategoriList = '';
                        data.forEach(function(kategori) {
                            kategoriList += kategori.nama_kategori + '<br>';
                        });
                        return kategoriList;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        return moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<i class="fa-solid fa-pen-to-square" onclick="editArtikel(' + row.id + ')"></i> ' +
                            '<span style="margin-right: 10px;"></span>' +
                            '<i class="fa-solid fa-trash" onclick="deleteArtikel(' + row.id + ')"></i>';
                    }
                }
            ],
            order: [[0, 'asc']]
        });
    });

    // Fungsi untuk menyimpan data yang diinput

function saveArtikel() {
    var id = $('#id').val();
    var method = (id === '') ? 'POST' : 'POST';
    var kategoriartikels = $('#kategoriartikels').val();

    var formData = new FormData();
    formData.append('judul', $('#judul').val());
    formData.append('konten', $('#konten').val());
    formData.append('photo', $('#photo').val());
    // Hanya menambahkan file foto ke FormData jika ada file yang dipilih
    var photoFile = $('#photo')[0].files[0];
    if (photoFile) {
        formData.append('photo', photoFile);
    }
    kategoriartikels.forEach(function(kategoriartikel) {
        formData.append('kategoriartikel_id[]', kategoriartikel );
    });
    $.ajax({
        url: '/artikel' + (id === '' ? '' : '/' + id),
        type: method,
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            Swal.fire({
                title: 'Sukses',
                text: 'Data berhasil disimpan',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearForm();
                    $('#tableArtikel').DataTable().ajax.reload();
                    $('#artikelFormModal').modal('hide');
                }
            });
        },
        error: function (error) {
            let errorMessage = '';
            const errorData = error.responseJSON.errors;
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

    // Edit data artikel
    function editArtikel(id) {
        $.ajax({
            url: '/artikel/' + id,
            type: 'GET',
            success: function (response) {
                // Mengisi formulir dengan data yang akan diedit
                $('#id').val(response.artikel.id);
                $('#judul').val(response.artikel.judul);
                // $('#konten').val(response.artikel.konten);
                $('#konten').summernote('code', response.artikel.konten);
                $('#photo-preview').attr('src', response.artikel.photo);
                $('#user_id').val(response.artikel.user_id);
                // var kategoriIds = response.kategoriartikels.map(function(kategori) {
                //     return kategori.id;
                // });
                // $('#kategoriartikels').val(kategoriIds);

            var selectedCategories = response.artikel.kategoriartikels.filter(function(category) {
                return category.pivot;
            }).map(function(category) {
                return category.id.toString();
            });
            $('#kategoriartikels option').each(function() {
                if (selectedCategories.indexOf($(this).val()) !== -1) {
                    $(this).prop('selected', true);
                }
            });
            
                $('#artikelFormModalLabel').text('Form Edit Artikel');
                $('#simpanArtikel').text('Simpan Perubahan');
                $('#artikelFormModal').modal('show');
            },
            error: function (error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Gagal mengambil data artikel.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Hapus data artikel
    function deleteArtikel(id) {
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
                    url: '/artikel/' + id,
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
                            $('#tableArtikel').DataTable().ajax.reload();
                        });
                    },
                    error: function (error) {
                        // Menampilkan notifikasi kesalahan
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal menghapus data artikel.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    }

    // Fungsi untuk menghapus isi form yang sudah diisi
    function clearForm() {
        $('#artikelFormModalLabel').text('Form Tambah Artikel');
        $('#simpanArtikel').text('Simpan');
        $('#artikelForm')[0].reset();
    }
</script>
