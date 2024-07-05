@extends('layout.frontend.app')
@section('css')
@include('layout.frontend.csssidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layout.frontend.sidebar')

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="dashboard-content pt-3 pb-2">
                <div class="col-lg-12">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <div class="e-profile">
                                <div class="row">
                                    <div class="col-12 col-sm-auto mb-3">
                                        <div class="mx-auto" style="width: 140px;">
                                            <div class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                                                @if($user->photo)
                                                    <img src="{{ asset($user->photo) }}" alt="Profile Photo" style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#changePhotoModal">
                                                <i class="fa fa-fw fa-camera"></i>
                                                <span>Ubah Photo</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <div class="text text-sm-left mb-2 mb-sm-0">
                                                    <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">{{ $user->name }}</h4>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="text-sm-right">
                                                    {{-- <span class="badge">user</span> --}}
                                                    <div class="text-muted"><small>Bergabung pada {{ $user->created_at }}</small></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content pt-3">
                                            <div class="tab-pane active">
                                                <form class="form" action="{{ route('update.user.store', ['id' => $user->id]) }}" method="POST" novalidate>
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Nama Lengkap</label>
                                                                <input class="form-control" type="text" name="name" placeholder="John Smith" value="{{ old('name', $user->name) }}" autocomplete="name" autofocus required>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input class="form-control" type="text" name="email" placeholder="XVbYi@example.com" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Tanggal Lahir</label>
                                                                <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>No HP</label>
                                                                <input type="number" class="form-control" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Jenis Kelamin</label>
                                                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                                                                    <option value="" disabled>Pilih</option>
                                                                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label>Alamat</label>
                                                                <textarea class="form-control" rows="5" name="alamat" placeholder="Masukkan alamat anda dengan lengkap disini" required>{{ old('alamat', $user->alamat) }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <p class="text-muted mt-3">Masukkan password anda untuk menyimpan perubahan.</p>
                                                        <!-- Password lama -->
                                                        <div class="form-group">
                                                            <label for="current_password">Password Sekarang</label>
                                                            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Password Sekarang">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="new_password" class="form-label mt-4">Ganti Password</label>
                                                        <p class="text-muted">Jika ingin mengganti password, silahkan masukkan password pada kolom di bawah ini</p>

                                                        <!-- Password baru -->
                                                        <div class="form-group">
                                                            <label for="new_password">Password Baru</label>
                                                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Password Baru" autocomplete="new-password">
                                                        </div>

                                                        <!-- Konfirmasi password baru -->
                                                        <div class="form-group">
                                                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                                                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="Konfirmasi Password Baru" autocomplete="new-password">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col d-flex justify-content-end">
                                                            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Change Photo -->
        <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('update.user.photo', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePhotoModalLabel">Upload Photo Baru</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="file" class="form-control-file" id="new_photo" name="new_photo" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Upload Photo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection
