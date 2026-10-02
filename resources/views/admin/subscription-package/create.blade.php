@extends('layouts.app')
@section('content')
    <div class="card mt-5 w-50 d-block mx-auto">
        <div class="card-header">
            <h1 class="h3 mb-0">Tambah Paket Langganan</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subscription-package.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Paket</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" placeholder="Contoh: Paket Premium" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga (Rp)</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" value="{{ old('price') }}" placeholder="Contoh: 50000" min="0" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>




                <div class="mb-3">
                    <label for="color" class="form-label">Warna Paket</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" class="form-control form-control-color" id="colorPicker"
                            value="{{ old('color', '#206bc4') }}" title="Pilih warna"
                            onchange="document.getElementById('color').value = this.value">
                        <input type="text" class="form-control @error('color') is-invalid @enderror" id="color"
                            name="color" value="{{ old('color', '#206bc4') }}" placeholder="#206bc4" required
                            onchange="document.getElementById('colorPicker').value = this.value">
                    </div>
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="3" placeholder="Deskripsi paket langganan..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
