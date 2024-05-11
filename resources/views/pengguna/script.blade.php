<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#tableUser').DataTable({
        ajax: {
            url: '/pengguna/datatables',
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
                    return '<i class="fa-solid fa-eye" onclick="detailPengguna(' + row.id + ')"></i> '
                }
            }
        ],
        order: [[0, 'asc']]
    });
});

    function detailPengguna(id) {
        $.ajax({
            url: '/penggunas/' + id,
            type: 'GET',
            success: function(response) {
                $('#namaPengguna').text(response.user.name);
                $('#emailPengguna').text(response.user.email);
                $('#rolePengguna').text(response.user.role);
                $('#jenisKelaminPengguna').text(response.user.jenis_kelamin);
                $('#tanggalLahirPengguna').text(response.user.tanggal_lahir);
                $('#noHpPengguna').text(response.user.no_hp);
                $('#alamatPengguna').text(response.user.alamat);

                // Set photo
                    if (response.user.photo) {
                        $('#photoPengguna').attr('src', '/pengguna/' + response.user.photo);
                    } else {
                        // Ganti dengan gambar placeholder dari Lorem Picsum
                        $('#photoPengguna').attr('src', 'https://picsum.photos/200/300');
                    }

                $('#detailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Request failed. Status: ' + xhr.status);
            }
        });
    }

</script>
