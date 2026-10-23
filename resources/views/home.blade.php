@extends('layouts.app')

@push('style')
    <style>
        .slick-prev:before,
        .slick-next:before {
            color: #333;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
         @if (Session::get('success'))
        <div class="alert alert-important alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l5 5l10 -10"></path>
                </svg>
                </div>
                <div>{{ Session::get('success')}}</div>
            </div>
            <a class="btn-close btn-close-white" data-bs-dismiss="alert"aria-label="close"></a>
        </div>
        @endif
        @if (Session::get('error'))
        <div class="alert alert-important alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l5 5l10 -10"></path>
                </svg>
                </div>
                <div>{{ Session::get('error')}}</div>
            </div>
            <a class="btn-close btn-close-white" data-bs-dismiss="alert"aria-label="close"></a>
        </div>
        @endif

        <div id="carousel-sample" class="carousel slide mu-3" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" alt=""
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStA1zREfwdFdKIgkJoGooLrFpQtrplGr88GtVKNwXeYQ&s=10" />
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" alt=""
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStA1zREfwdFdKIgkJoGooLrFpQtrplGr88GtVKNwXeYQ&s=10" />
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" alt=""
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStA1zREfwdFdKIgkJoGooLrFpQtrplGr88GtVKNwXeYQ&s=10" />
                </div>
            </div>
            <a class="carousel-control-prev" data-bs-target="#carousel-sample" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" data-bs-target="#carousel-sample" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>

        {{-- Section: Data Paket Langganan --}}
        <div class="mt-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-yellow text-yellow-fg p-2"><i class="fa-solid fa-crown fs-3"></i></span>
                <h2 class="mt-3 text-dark">Paket Langganan</h2>
            </div>
            <div class="row g-4">
                {{-- Looping data paket langganan terbaru --}}
                @forelse ($subscriptionPackages as $subscriptionPackage)
                    <div class="col-md-4">
                        {{-- Card paket langganan dapat diklik menuju detail paket langganan --}}
                        <a href="{{ route('subscription-package.detail', $subscriptionPackage->id) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-0"
                                style="background: linear-gradient(135deg, {{ $subscriptionPackage->color }} 0%, #ffffff 100%); cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                                onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.12)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                                <div class="card-body text-center text-dark d-flex flex-column justify-content-center align-items-center">
                                    {{-- Nama paket langganan --}}
                                    <h2 class="fw-bold">{{ $subscriptionPackage->name }}</h2>
                                    <p class="text-secondary fw-bold mb-1">PACKAGE</p>
                                    
                                    {{-- Deskripsi paket langganan jika ada --}}
                                    @if ($subscriptionPackage->description)
                                        <p class="text-muted small mb-2">{{ $subscriptionPackage->description }}</p>
                                    @endif

                                    {{-- Harga paket langganan dalam format rupiah --}}
                                    <div class="text-center mt-2">
                                        <span class="fs-2 fw-bold text-warning"
                                            style="color: #D4AF37 !important;">Rp {{ number_format($subscriptionPackage->price, 0, ',', '.') }}</span>
                                        <br>
                                        <span class="fw-bold text-secondary">/30 Hari</span>
                                    </div>

                                   
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-secondary">Paket langganan belum tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <h2 class="mt-3 text-dark" style="font-weight: bold">Buku Baru Dirilis</h2>
            </div>
            <div id="wrapper-slider">
                @forelse ($latestBooks as $book)
                    <div class="px-2">
                        <div class="card">
                            <div class="card-body mw-100">
                                <img class="d-block mx-auto w-75 h-50"
                                    src="{{ $book->cover ? asset($book->cover) : 'https://via.placeholder.com/150' }}"
                                    alt="">
                                <div class="d-flex gap-2 mt-2">
                                    <p class="badge"><i class="fa-solid fa-mobile"></i>PDF</p>
                                    <p class="badge">3+</p>
                                </div>
                                <div>
                                    <h5><span style="font-size: 0.8rem;" class="text-secondary">{{ $book->writer ?? 'Unknown Author' }}</span>
                                        <br><span style="font-size: 1rem">{{ $book->title ?? 'No Title' }}</span>
                                    </h5>
                                    <h4 style="font-size: 1.2rem; font-weight: bold;">Rp {{ number_format($book->price ?? 0, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-secondary">Buku baru belum tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <h2 class="mt-3 text-dark" style="font-weight: bold;">Buku Gratis</h2>
            </div>
            <div class="row">
                @forelse ($freeBooks as $book)
                    <div class="col-4">
                        <div class="card d-flex flex-column">
                            <div class="row row-0 flex-fill">
                                <div class="col-md-3">
                                    <a href="#">
                                        <img src="{{ asset($book->cover)}}" alt="card-side-image" class="w-100 h-100 object-cover">
                                    </a>
                                </div>
                                <div class="col">
                                    <div class="card-body h-full d-flex flex-column">
                                        <h3 class="card-title">
                                            <div class="badge"><i class="fa-solid fa-mobile"></i>PDF</div>
                                        </h3>
                                        <div class="text-secondary">
                                            <span style="font-size: 0.8rem;">{{ $book->writer }}</span>
                                            <br><span class="text-dark fw-bold" style="font-size: 0.95rem;">{{ $book->title }}</span>
                                        </div>
                                        <div class="d-flex align-items-center pt-4 mt-auto">
                                            <h3>
                                            <span class="text-dark">Rp 0</span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-secondary">Buku gratis belum tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('document').ready(function() {
            $('#wrapper-slider').slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

        })
    </script>
@endpush