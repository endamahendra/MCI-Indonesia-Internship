<!-- Modal untuk Form Product -->
<div class="modal fade" id="productFormModal" tabindex="-1" role="dialog" aria-labelledby="productFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productFormModalLabel">Form Tambah Data Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="productForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id">
                    <div class="col-md-12">
                        <input type="text" id="sku" class="form-control" placeholder="SKU">
                    </div>
                    <div class="col-md-12">
                        <input type="text" id="deskripsi" class="form-control" placeholder="Deskripsi">
                    </div>
                    <div class="col-md-12">
                        <input type="number" id="harga" class="form-control" placeholder="Harga">
                    </div>
                    <div class="col-md-12">
                        <input type="number" id="stok" class="form-control" placeholder="Stok">
                    </div>
                    <div class="col-md-12">
                        <input type="number" id="diskon" class="form-control" placeholder="diskon">
                    </div>
                     <div class="col-md-12">
                        <select class="s-example-basic-multiple" id="category_id" multiple="multiple" >
                            @foreach($categorys as $category)
                                <option class="form-control" value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <input type="file" id="photo" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="saveProduct()" id="simpanProduct" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#category_id').select2({
        placeholder: "Pilih Kategori",
        allowClear: true
    });
});
</script>




