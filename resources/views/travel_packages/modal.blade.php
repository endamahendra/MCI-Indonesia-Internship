<!-- Modal untuk Form Travel Package -->
<div class="modal fade" id="travelPackageFormModal" tabindex="-1" role="dialog" aria-labelledby="travelPackageFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="travelPackageFormModalLabel">Form Tambah Paket Perjalanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="travelPackageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id">
                    <div class="col-md-12">
                        <input type="text" id="deskripsi" class="form-control" placeholder="Deskripsi">
                    </div>
                    <div class="col-md-12">
                        <input type="date" id="tanggal" class="form-control" placeholder="Tanggal">
                    </div>
                    <div class="col-md-12">
                        <input type="number" id="target" class="form-control" placeholder="Target">
                    </div>
                    <div class="col-md-12">
                        <input type="file" id="photo" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="saveTravelPackage()" id="simpanTravelPackage" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
<script>
    $('#photo').on('change', function() {
    var file = this.files[0];
    console.log(file); // Tambahkan ini untuk memeriksa apakah file berhasil dipilih
});
</script>
