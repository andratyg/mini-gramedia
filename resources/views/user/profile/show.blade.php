@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row g-4">
            {{-- 1. SIDEBAR KIRI: Inisial Avatar, Nama User, Profil Summary --}}
            <div class="col-lg-3 col-md-4">
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-body p-4 text-start">
                        @php
                            $words = preg_split('/\s+/', trim($user->name));
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($user->name, 0, 2));
                            }
                        @endphp

                        {{-- Avatar User  & Nama --}}
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle bg-secondary-subtle text-secondary fw-bold fs-2 d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 54px; height: 54px; min-width: 54px; background-color: #e2e8f0;">
                                {{ $initials }}
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="fw-bold text-dark mb-0 text-truncate" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </h4>
                            </div>
                        </div>

                        {{-- Profil Summary --}}
                        <h5 class="fw-bold text-primary mb-3">Profil Summary</h5>

                        <div class="d-flex flex-column gap-2">
                            {{-- Koleksi Buku: menghitung jumlah buku yang dimiliki/bisa diakses --}}
                            <div class="d-flex justify-content-between align-items-center text-secondary">
                                <span>Koleksi Buku</span>
                                <span class="fw-bold text-dark">{{ $myBooks->count() }}</span>
                            </div>
                            {{-- Langganan: menghitung jumlah data dari table subscription_package_users sesuai id pengguna --}}
                            <div class="d-flex justify-content-between align-items-center text-secondary">
                                <span>Langganan</span>
                                <span class="fw-bold text-dark">{{ $allSubscriptions->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. KONTEN KANAN: Transaksi & Dropdown Filter --}}
            <div class="col-lg-9 col-md-8">
                {{-- Header: Judul Transaksi & Filter Dropdown --}}
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-0 fs-1">Transaksi</h2>
                    </div>
                    <div>
                        <label for="filterJenisTransaksi" class="form-label text-secondary small fw-medium mb-1">
                            Jenis Transaksi
                        </label>
                        {{-- Dropdown Filter secara default menampilkan Paket Langganan --}}
                        <select id="filterJenisTransaksi" class="form-select form-select-sm" style="min-width: 170px;">
                            <option value="paket_langganan" selected>Paket Langganan</option>
                            <option value="buku">Buku</option>
                        </select>
                    </div>
                </div>

                {{-- Kontainer 1: Daftar Transaksi Paket Langganan (Default Ditampilkan) --}}
                <div id="wrapper-paket-langganan">
                    @forelse ($allSubscriptions as $sub)
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-4">
                                {{-- Baris Atas: Tanggal Data Dibuat (created_at) & Badge Status --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-secondary small">
                                        {{ $sub->created_at->format('d M Y, H:i') }}
                                    </span>
                                    <span class="badge px-3 py-2 fw-semibold"
                                        style="background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9;">
                                        Transaksi Sukses
                                    </span>
                                </div>

                                <hr class="my-3 text-muted opacity-25">

                                {{-- Baris Bawah: Nama Paket, Tanggal Habis Masa Berlaku (expired_date), & Harga Paket --}}
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <h4 class="fw-bold text-dark mb-0">
                                            {{ $sub->subscriptionPackage->name ?? 'Paket Langganan' }}
                                        </h4>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 fw-semibold text-white">
                                            Berlaku sd : {{ \Carbon\Carbon::parse($sub->expired_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="text-sm-end">
                                        <div class="text-secondary small mb-1">Total Pembayaran</div>
                                        <div class="fw-bold fs-3 text-dark">
                                            Rp {{ number_format($sub->subscriptionPackage->price ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
                            <p class="text-secondary mb-0">Belum ada riwayat transaksi paket langganan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Kontainer 2: Tampilan Koleksi / Transaksi Buku --}}
                <div id="wrapper-buku" class="d-none">
                    {{-- Jika ada koleksi buku yang dimiliki user (dari langganan maupun checkout) --}}
                    @if ($myBooks->isNotEmpty())
                        <div class="d-flex flex-column gap-3">
                            @foreach ($myBooks as $book)
                                <div class="card border-0 shadow-sm rounded-3 mb-2">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-primary-subtle text-primary px-3 py-1 fw-semibold">
                                                {{ $book->bookCategory->name ?? 'Buku' }}
                                            </span>
                                            <span class="badge px-3 py-2 fw-semibold"
                                                style="background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9;">
                                                Tersedia di Koleksi
                                            </span>
                                        </div>

                                        <hr class="my-3 text-muted opacity-25">

                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($book->cover)
                                                    <img src="{{ asset($book->cover) }}" alt="{{ $book->title }}"
                                                        class="rounded shadow-sm" style="width: 48px; height: 64px; object-fit: cover;">
                                                @else
                                                    <div class="avatar avatar-md rounded bg-light text-secondary fs-3 d-flex align-items-center justify-content-center"
                                                        style="width: 48px; height: 64px;">
                                                        <i class="fa-solid fa-book"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="fw-bold text-dark mb-1">
                                                        {{ $book->title }}
                                                    </h4>
                                                    <div class="text-secondary small">
                                                        Penulis: {{ $book->writer ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-sm-end">
                                                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold">
                                                    Baca Buku
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else 
                        <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
                            <p class="text-secondary mb-0">Belum ada koleksi atau riwayat transaksi buku.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('filterJenisTransaksi');
            const wrapperLangganan = document.getElementById('wrapper-paket-langganan');
            const wrapperBuku = document.getElementById('wrapper-buku');

            if (filterSelect && wrapperLangganan && wrapperBuku) {
                filterSelect.addEventListener('change', function() {
                    if (this.value === 'paket_langganan') {
                        wrapperLangganan.classList.remove('d-none');
                        wrapperBuku.classList.add('d-none');
                    } else if (this.value === 'buku') {
                        wrapperLangganan.classList.add('d-none');
                        wrapperBuku.classList.remove('d-none');
                    }
                });
            }
        });
    </script>
@endpush
