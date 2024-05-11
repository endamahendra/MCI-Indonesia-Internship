<!-- Modal untuk Form Artikel -->
<div class="modal fade bd-example-modal-xl" id="artikelFormModal" tabindex="-1" role="dialog" aria-labelledby="artikelFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="artikelFormModalLabel">Artikel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="artikelForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id">
                    <div class="col-md-12">
                        <input type="text" id="judul" class="form-control" placeholder="Judul">
                    </div>
                    <div class="col-md-12">
                        <textarea id="konten" class="form-control" placeholder="Konten Artikel"></textarea>
                    </div>
                    <div class="col-md-12">
                        <input type="file" id="photo" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <select id="kategoriartikels" class="form-select" multiple="multiple">
                            @foreach($kategoriartikels as $kategoriartikel)
                                <option class="form-control" value="{{ $kategoriartikel->id }}">{{ $kategoriartikel->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="saveArtikel()" id="simpanArtikel" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    $('#kategoriartikels').select2();
});
$(document).ready(function() {
  $('#konten').summernote({
      height: 200,
      minHeight: null,
      maxHeight: null,
      focus: true
    });
});
</script>
