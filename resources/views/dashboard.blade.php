@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <p>Welcome, {{ Auth::user()->name }}</p>
                    <p>Your email: {{ Auth::user()->email }}</p>
                    <p>Your role: {{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
