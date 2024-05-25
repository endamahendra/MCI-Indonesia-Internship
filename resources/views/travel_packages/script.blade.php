<script>
    // Fungsi untuk menampilkan data
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#tableTravelPackage').DataTable({
            // dom: 'Bfrtip',
            // buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            ajax: {
                url: '/travel-package/datatables',
                type: 'GET',
                "serverSide": true,
                "processing": true,
            },
            columns: [
                { data: 'deskripsi' },
                { data: 'tanggal' },
                {
                    data: 'target',
                    render: function(data, type, row) {
                        // Ubah angka menjadi format rupiah
                        return formatRupiah(data, 'Rp ');
                    }
                },
                {
                    "data": "photo",
                    "render": function(data, type, row, meta) {
                        return '<img src="{{asset('')}}' + data + '" alt="Travel Package Image" style="width: 100px; height: auto;" />';
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
                    render: function(data, type, row) {
                        return '<i class="fa-solid fa-pen-to-square" onclick="editTravelPackage(' + row.id + ')"></i> ' +
                            '<span style="margin-right: 10px;"></span>' +
                            '<i class="fa-solid fa-trash" onclick="deleteTravelPackage(' + row.id + ')"></i>';
                    }
                }
            ],
            order: [
                [0, 'asc']
            ]
        });
    });

    // Fungsi untuk menyimpan data yang diinput
    function saveTravelPackage() {
        var id = $('#id').val();
        var method = (id === '') ? 'POST' : 'POST';

        var formData = new FormData();
        formData.append('id', $('#id').val());
        formData.append('deskripsi', $('#deskripsi').val());
        formData.append('tanggal', $('#tanggal').val());
        formData.append('target', $('#target').val());

        // Hanya menambahkan file foto ke FormData jika ada file yang dipilih
        var photoFile = $('#photo')[0].files[0];
        if (photoFile) {
            formData.append('photo', photoFile);
        }

        $.ajax({
            url: '/travel-package' + (id === '' ? '' : '/' + id),
            type: method,
            data: formData,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                Swal.fire({
                    title: 'Sukses',
                    text: 'Data berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearForm();
                        $('#tableTravelPackage').DataTable().ajax.reload();
                        $('#travelPackageFormModal').modal('hide');
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

    // Fungsi untuk mengedit data TravelPackage
    function editTravelPackage(id) {
        $.ajax({
            url: '/travel-package/' + id,
            type: 'GET',
            success: function(response) {
                // Set nilai-nlai TravelPackage ke dalam elemen-elemen formulir
                $('#id').val(response.travel_package.id);
                $('#deskripsi').val(response.travel_package.deskripsi);
                $('#tanggal').val(response.travel_package.tanggal);
                $('#target').val(response.travel_package.target);

                // Set foto jika ada
                if (response.travel_package.photo) {
                    $('#photo').attr('src', '/images/travel/' + response.travel_package.photo); // Atur atribut src untuk menampilkan gambar
                } else {
                    $('#photo').removeAttr('src'); // Hapus gambar jika tidak ada
                }

                // Tampilkan modal
                $('#travelPackageFormModalLabel').text('Form Edit Data');
                $('#simpanTravelPackage').text('Simpan Perubahan');
                $('#travelPackageFormModal').modal('show');
            },
            error: function(error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Gagal mengambil data TravelPackage.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Fungsi untuk menghapus data TravelPackage
    function deleteTravelPackage(id) {
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
                    url: '/travel-package/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        // Menampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Data berhasil dihapus',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Memuat ulang data setelah penghapusan
                            $('#tableTravelPackage').DataTable().ajax.reload();
                        });
                    },
                    error: function(xhr, status, error) {
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
                                text: 'Gagal menghapus data TravelPackage.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        });
    }

    // Fungsi untuk menghapus isi form yang sudah diisi
    function clearForm() {
        $('#id').val('');
        $('#deskripsi').val('');
        $('#tanggal').val('');
        $('#target').val('');
        $('#photo').val('');
    }
</script>
