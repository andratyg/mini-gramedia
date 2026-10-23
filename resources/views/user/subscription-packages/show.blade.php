@extends('layouts.app')

@push('style')
    <style>
        .package-hero-banner {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            padding: 48px 32px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            background: linear-gradient(135deg, {{ $subscriptionPackage->color }}22 0%, #ffffff 60%, {{ $subscriptionPackage->color }}11 100%);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .package-hero-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, {{ $subscriptionPackage->color }}33 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .book-card-item {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
        }

        .book-card-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .book-cover-img {
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        {{-- Tombol Kembali --}}
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Beranda
        </a>

        {{-- Banner / Header Paket Langganan --}}
        <div class="package-hero-banner text-center mb-4">
            <h1 class="display-5 fw-bold mb-2" style="color: {{ $subscriptionPackage->color }}; letter-spacing: 1px;">
                {{ strtoupper($subscriptionPackage->name) }} PACKAGE
            </h1>
            <p class="fs-4 text-secondary mb-0">
                {{ $subscriptionPackage->description ?? 'Nikmati akses membaca koleksi buku pilihan terbaik dalam paket ini.' }}
            </p>
        </div>

        {{-- Card Informasi Detail Paket & Tombol Langganan --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
            <div class="card-body py-3 px-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $subscriptionPackage->name }} Package</h2>
                        <small class="text-muted"><i class="fa-solid fa-book me-1"></i>{{ $books->count() }} buku tersedia</small>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end">
                            <span class="fs-2 fw-bold text-dark">
                                Rp {{ number_format($subscriptionPackage->price, 0, ',', '.') }}
                            </span>
                            <span class="text-muted small fw-semibold">/ 30 Hari</span>
                        </div>
                        <a href="{{ route('subscription-package.checkout.page', $subscriptionPackage->id) }}"
                            class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-crown me-1"></i> Langganan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Bar: Pencarian buku dalam paket langganan ini --}}
        <div class="my-4">
            <form action="{{ route('subscription-package.detail', $subscriptionPackage->id) }}" method="GET">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="10" cy="10" r="7" />
                                    <line x1="21" y1="21" x2="15" y2="15" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-rounded shadow-sm"
                                placeholder="Cari dalam paket ini..." autocomplete="off">
                            @if (request('search'))
                                <span class="input-icon-addon">
                                    <a href="{{ route('subscription-package.detail', $subscriptionPackage->id) }}"
                                        class="text-muted" title="Reset pencarian">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Pesan Hasil Pencarian jika ada --}}
        @if (request('search'))
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-secondary mb-0">
                    Hasil pencarian untuk: "<strong>{{ request('search') }}</strong>" ({{ $books->count() }} buku ditemukan)
                </p>
                <a href="{{ route('subscription-package.detail', $subscriptionPackage->id) }}" class="btn btn-sm btn-link text-decoration-none">
                    Tampilkan Semua
                </a>
            </div>
        @endif

        {{-- Daftar Buku yang Tertaut pada Paket Langganan --}}
        @if ($books->isEmpty())
            <div class="alert alert-info text-center py-5 shadow-sm border-0" style="border-radius: 12px;">
                <i class="fa-solid fa-book-open fs-1 mb-3 text-secondary d-block"></i>
                @if (request('search'))
                    Tidak ada buku dalam paket <strong>{{ $subscriptionPackage->name }}</strong> yang cocok dengan kata kunci
                    "<strong>{{ request('search') }}</strong>".
                    <div class="mt-3">
                        <a href="{{ route('subscription-package.detail', $subscriptionPackage->id) }}"
                            class="btn btn-sm btn-outline-secondary">Reset Pencarian</a>
                    </div>
                @else
                    Belum ada buku yang tertaut pada paket langganan <strong>{{ $subscriptionPackage->name }}</strong>.
                @endif
            </div>
        @else
            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
                @foreach ($books as $book)
                    <div class="col">
                        <div class="card h-100 book-card-item border-0 shadow-sm">
                            <div class="card-body p-3 d-flex flex-column">
                                {{-- Sampul Buku --}}
                                <img class="d-block w-100 book-cover-img mb-2"
                                    src="{{ $book->cover ? (Str::startsWith($book->cover, 'http') ? $book->cover : asset($book->cover)) : 'https://via.placeholder.com/150' }}"
                                    alt="{{ $book->title }}">

                                {{-- Badges Format dan Halaman --}}
                                <div class="d-flex gap-1 mb-2">
                                    <span class="badge bg-light text-secondary border" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-file-pdf text-danger me-1"></i>PDF
                                    </span>
                                    <span class="badge bg-light text-secondary border" style="font-size: 0.7rem;">
                                        {{ $book->page_of_book ? $book->page_of_book . ' hlm' : '17+' }}
                                    </span>
                                </div>

                                {{-- Penulis & Judul Buku --}}
                                <div class="mt-auto">
                                    <small class="text-secondary d-block text-truncate mb-1" style="font-size: 0.75rem;" title="{{ $book->writer }}">
                                        {{ $book->writer }}
                                    </small>
                                    <h5 class="fw-bold text-dark text-truncate mb-0" style="font-size: 0.85rem;" title="{{ $book->title }}">
                                        {{ $book->title }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
