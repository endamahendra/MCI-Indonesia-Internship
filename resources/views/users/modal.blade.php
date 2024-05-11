<!-- Modal untuk Form Admin -->
<div class="modal fade" id="userFormModal" tabindex="-1" role="dialog" aria-labelledby="userFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userFormModalLabel">Form Tambah Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="userForm">
                    @csrf
                    <input type="hidden" id="id">papp
                    <div class="col-md-12">
                        <input type="text" id="name" class="form-control" placeholder="Nama Admin">
                    </div>
                    <div class="col-md-12">
                        <input type="email" id="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-md-12">
                        <input type="password" id="password" class="form-control" placeholder="Password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="saveUser()" id="simpanUser" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
