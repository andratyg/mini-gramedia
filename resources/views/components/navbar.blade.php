<header class="navbar navbar-expand-md">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- BEGIN NAVBAR LOGO -->
        <a href="{{ route('home') }}" aria-label="Tabler" class="navbar-brand navbar-brand-autodark me-3">
            <img src="data:image/png;base64,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"
                width="150" height="60">
        </a><!-- END NAVBAR LOGO -->

        <div class="navbar-nav mx-auto w-50">
            @if (Auth::check() && Auth::user()->role == 'admin')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.book-categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.book-categories.index') ? 'active' : '' }}">Kategori
                        Buku</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscription-package.index') }}"
                        class="nav-link {{ request()->routeIs('admin.subscription-package.index') ? 'active' : '' }}">Paket
                        Langganan</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.books.index') }}"
                        class="nav-link {{ request()->routeIs('admin.books.index') ? 'active' : '' }}">Buku</a>
                </li>
            @else
                {{-- Dropdown Kategori Buku --}}
                <div class="dropdown mt-2">
                    <a href="#" class="btn btn-light dropdown-toggle pt-2 me-2"
                        data-bs-toggle="dropdown">Kategori</a>
                    <div class="dropdown-menu dropdown-menu-card" style="min-width: 600px;">
                        <div class="p-3">
                            <div class="row g-2">
                                @forelse ($categories as $category)
                                    <div class="col-3">
                                        {{-- Link menuju halaman daftar buku berdasarkan kategori yang diklik --}}
                                        <a href="{{ route('category.books', $category->id) }}"
                                            class="text-decoration-none">
                                            <div class="card py-2 text-dark">
                                                <div class="card-body p-2 text-center">{{ $category->name }}</div>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="card py-2">
                                            <div class="card-body p-2 text-center text-muted">Tidak ada kategori</div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search Form --}}
                <form action="{{ route('search') }}" method="GET" class="w-100 my-auto">
                    <div class="input-icon w-100 py-2">
                        <input type="text" name="query" value="{{ request('query') }}"
                            class="form-control form-control-rounded" placeholder="Cari judul, produk, buku, penulis..."
                            autocomplete="off">
                        <button type="submit" class="input-icon-addon border-0 bg-transparent" style="cursor: pointer;"
                            title="Cari">
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

                {{-- Keranjang --}}
                <div class="mt-3 ms-2">
                    <i class="fa-solid fa-cart-arrow-down fs-3 text-dark"></i>
                </div>
            @endif
        </div>

        <div class="navbar-nav flex-row order-md-last ms-auto gap-2">
            @if (Auth::check())
                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-light">Daftar</a>
            @endif
        </div>
    </div>
</header>
