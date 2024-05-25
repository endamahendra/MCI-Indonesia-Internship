<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5">
                            <img id="photoPengguna" src="" class="img-fluid rounded border border-dark mx-auto d-block" alt="Photo Pengguna">
                        </div>
                        <div class="col-md-7">
                            <h5 class="modal-title mb-4" id="namaPengguna"></h5>
                            <ul class="list-group mb-3">
                                <li class="list-group-item"><strong>Email:</strong> <span id="emailPengguna"></span></li>
                                <li class="list-group-item"><strong>Peran:</strong> <span id="rolePengguna"></span></li>
                                <li class="list-group-item"><strong>Jenis Kelamin:</strong> <span id="jenisKelaminPengguna"></span></li>
                                <li class="list-group-item"><strong>Tanggal Lahir:</strong> <span id="tanggalLahirPengguna"></span></li>
                                <li class="list-group-item"><strong>No. HP:</strong> <span id="noHpPengguna"></span></li>
                                <li class="list-group-item"><strong>Alamat:</strong> <span id="alamatPengguna"></span></li>
                                {{-- <li class="list-group-item"><strong>Total Order:</strong> 10</li> --}}
                                {{-- <li class="list-group-item"><strong>Total Amount:</strong> $500</li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
