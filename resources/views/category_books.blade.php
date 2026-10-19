@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Header: Judul Kategori & Form Search / Sort Bar --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                {{-- Menampilkan nama kategori buku yang sedang diakses --}}
                <h2 class="text-dark fw-bold mb-0">Buku {{ $category->name }}</h2>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                {{-- Form untuk Search & Sort dengan metode GET menuju route category.books --}}
                <form action="{{ route('category.books', $category->id) }}" method="GET"
                    class="d-flex flex-wrap align-items-center gap-2">

                    {{-- Dropdown pengurutan buku --}}
                    {{-- onchange="this.form.submit()" otomatis mengirim form saat opsi dipilih --}}
                    <select name="sort" class="form-select form-select-rounded" onchange="this.form.submit()"
                        style="width: auto;">
                        {{-- Opsi 1: Buku Terbaru (default) --}}
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Buku Terbaru
                        </option>
                        {{-- Opsi 2: Harga Termurah --}}
                        <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Harga Termurah
                        </option>
                        {{-- Opsi 3: Harga Termahal --}}
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Harga Termahal
                        </option>
                    </select>

                    {{-- Search bar untuk mencari judul buku atau penulis --}}
                    <div class="input-icon">
                        {{-- Input teks pencarian, nilai lama tetap dipertahankan dengan request('search') --}}
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-rounded" placeholder="cari judul atau penulis"
                            autocomplete="off" style="min-width: 240px;">
                        <button type="submit" class="input-icon-addon border-0 bg-transparent" style="cursor: pointer;" title="Cari">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Daftar buku sesuai kategori terkait --}}
        @if ($books->isEmpty())
            {{-- Tampilan jika tidak ada buku ditemukan --}}
            <div class="alert alert-info text-center py-4">
                <i class="fa-solid fa-book-open fs-2 mb-2 d-block"></i>
                @if (request('search'))
                    Tidak ada buku dalam kategori <strong>{{ $category->name }}</strong> yang cocok dengan kata kunci
                    "<strong>{{ request('search') }}</strong>".
                @else
                    Belum ada buku untuk kategori <strong>{{ $category->name }}</strong>.
                @endif
            </div>
        @else
            {{-- Menampilkan kumpulan card buku sesuai kategori --}}
            <div class="row g-3">
                @foreach ($books as $book)
                    <div class="col-6 col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                {{-- Gambar Cover Buku --}}
                                <img class="d-block mx-auto w-75 h-50"
                                    src="{{ $book->cover ? (Str::startsWith($book->cover, 'http') ? $book->cover : asset($book->cover)) : 'https://via.placeholder.com/150' }}"
                                    alt="{{ $book->title }}" style="object-fit: cover; max-height: 180px;" />
                                
                                {{-- Badge Format (PDF) dan Usia/Halaman --}}
                                <div class="d-flex gap-2 mt-2">
                                    <p class="badge"><i class="fa-solid fa-mobile"></i>PDF</p>
                                    <p class="badge">{{ $book->page_of_book }}</p>
                                </div>

                                {{-- Informasi Penulis, Judul, dan Harga Buku --}}
                                <div>
                                    <h5>
                                        <span style="font-size: 0.8rem;"
                                            class="text-secondary">{{ $book->writer }}</span><br>
                                        <span style="font-size: 1rem;">{{ $book->title }}</span>
                                    </h5>
                                    <h4 style="font-size: 1.2rem; font-weight: bold;">
                                        Rp {{ number_format($book->price, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
