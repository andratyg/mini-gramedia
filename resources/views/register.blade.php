@extends('layouts.app')

@section('content')
    {{-- arahkan submit form ke route yang bernama register.store --}}
    <form action="{{ route('register.store') }}" method="POST" class="form-fieldset w-50
    bg-white mx-auto mt-5">
        @csrf
        <div class="mb-3">
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control @error('name')
                is-invalid
            @enderror" autocomplete="off" name="name" value="{{ old('name') }}" />

            {{-- memanggil error validasi @error('nama_input') --}}
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label required">Email</label>
            <input type="email" class="form-control @error('email')
                is-invalid
            @enderror" autocomplete="off" name="email" value="{{ old('email') }}" />

            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control @error('password')
                is-invalid
            @enderror" autocomplete="off" name="password" value="{{ old('password') }}" />

            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control @error('password')
            is-invalid
            @enderror" autocomplete="off" name="password_confirmation" value="{{ old('password_confirmation') }}" />
        </div>
        <button type="submit" class="btn btn-primary w-100">Buat Akun</button>
    </form>
@endsection
