<!-- Modal untuk Form Jenjang -->
<div class="modal fade" id="categoryFormModal" tabindex="-1" role="dialog" aria-labelledby="categoryFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryFormModalLabel">Form Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="categoryForm">
                    @csrf
                    <input type="hidden" id="id">
                    <div class="col-md-12">
                        <input type="text" id="nama_kategori" class="form-control" placeholder="Nama Kategori" oninput="generateDeskripsi()">
                    </div>

                        <input type="hidden" id="deskripsi">
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="saveCategory()" id="simpanCategory" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        function generateDeskripsi() {
        var namaKategoriValue = document.getElementById('nama_kategori').value;
        var deskripsiValue = namaKategoriValue.trim().toLowerCase().replace(/\s+/g, '-');
        document.getElementById('deskripsi').value = deskripsiValue;
    }
</script>
