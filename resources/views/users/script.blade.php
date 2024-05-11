<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#tableUser').DataTable({
        // dom: 'Bfrtip',
        // buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        ajax: {
            url: '/admin/datatables',
            type: 'GET',
            "serverSide": true,
            "processing": true,
        },
        columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return moment(data).format('YYYY-MM-DD HH:mm:ss');
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return '<i class="fa-solid fa-pen-to-square" onclick="editUser(' + row.id + ')"></i> ' +
                           '<span style="margin-right: 10px;"></span>' +
                           '<i class="fa-solid fa-trash" onclick="deleteUser(' + row.id + ')"></i>';
                }
            }
        ],
        order: [[0, 'asc']]
    });
});

//fungsi untuk menyimpan data yang diinput
function saveUser() {
    var id = $('#id').val();
    var method = (id === '') ? 'POST' : 'PUT';
    var data = {
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        role: $('#role').val(),
    };
    $.ajax({
        url: '/admin' + (method === 'POST' ? '' : '/' + id),
        type: method,
        data: data,
        success: function (response) {
            Swal.fire({
                title: 'Sukses',
                text: 'Data pengguna berhasil disimpan',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearForm();
                    $('#tableUser').DataTable().ajax.reload();
                    $('#userFormModal').modal('hide');
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

//edit data pengguna
function editUser(id) {
    $.ajax({
        url: '/admin/' + id,
        type: 'GET',
        success: function (response) {
            $('#id').val(response.user.id);
            $('#name').val(response.user.name);
            $('#email').val(response.user.email);
            $('#role').val(response.user.role);
            // Mengisi formulir dengan data yang akan diedit
            $('#userFormModalLabel').text('Form Edit Data');
            $('#simpanUser').text('Simpan Perubahan');
            $('#userFormModal').modal('show');
        },
        error: function (error) {
            Swal.fire({
                title: 'Error',
                text: 'Gagal mengambil data pengguna.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

//hapus data pengguna
function deleteUser(id) {
    // Menampilkan modal konfirmasi penghapusan
    Swal.fire({
        title: 'Konfirmasi Hapus Data',
        text: 'Apakah Anda yakin ingin menghapus data pengguna ini?',
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
                url: '/admin/' + id,
                type: 'DELETE',
                success: function (response) {
                    // Menampilkan notifikasi sukses
                    Swal.fire({
                        title: 'Sukses',
                        text: 'Data pengguna berhasil dihapus',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Memuat ulang data setelah penghapusan
                        $('#tableUser').DataTable().ajax.reload();
                    });
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 403) {
                        Swal.fire({
                            title: 'Peringatan',
                            text: 'Data Admin dengan ID 1 tidak bisa dihapus.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal menghapus data pengguna.',
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
    $('#name').val('');
    $('#email').val('');
    $('#password').val('');
    $('#role').val('');
}
</script>
