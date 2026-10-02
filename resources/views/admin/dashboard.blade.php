    @extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Selamat Datang di Halaman Admin</h1>
        <p class="text-center">Welcome, {{ Auth::user()->name }}</p>
    </div>
@endsection
