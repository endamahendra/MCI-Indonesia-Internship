@extends('layout.frontend.app')
@section('css')
@include('layout.frontend.csssidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
@include('layout.frontend.sidebar')

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="dashboard-content pt-3">
                <div class="card">
                    <div class="card-body">
                        <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
                        <p>This is your user dashboard.</p>
                        <p>You can update your profile here.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
