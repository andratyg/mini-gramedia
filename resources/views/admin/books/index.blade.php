@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between">
            <h3>Data Buku</h3>
            <a href="{{ route('admin.books.create') }}" class="btn btn-success">Tambah Data Buku</a>

        </div>
        <table class="table table-bordered mt-3" id="data-buku">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sampul Buku</th>
                    <th>Judul</th>
                    <th>Katergori</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>

        </table>
    </div>

    {{-- modal detail --}}

    <div class="modal modal-blur fade" id="modal-blurred" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <img src="" id="data-cover" class="img-fluid rounded" alt="Sampul buku">
                        </div>
                        <div class="col-8">
                            <h3 class="text-primary" id="data-title"></h3>
                            <span class="badge bg-secondary-lt mb-3" id="data-category"></span>
                            <div class="row">
                                <div class="col-6">
                                    <p>Harga <br> <span id="data-price" class="text-success"></span></p>
                                    <p>Penerbit <br> <span id="data-publisher"></span></p>
                                    <p>Tanggal Rilis <br> <span id="data-release-date"></span></p>
                                </div>
                                <div class="col-6">
                                    <p>Penulis <br> <span id="data-writer"></span></p>
                                    <p>Bahasa <br> <span id="data-language"></span></p>
                                    <p>Jumlah Halaman <br> <span id="data-page-of-book"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div>

                        <div class="text-secondary fw-bold text-uppercase mb-2">DESKRIPSI BUKU</div>
                        <div class="border rounded p-3 bg-light" style="max-height: 180px; overflow-y: auto;">
                            <div id="data-description" class="text-secondary"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#data-buku").DataTable({
                // menampilkan ikon loading
                processing: true,
                // menggunakan server side (data diproses di controller)
                serverSide: true,
                // routing yang memproses datatables
                ajax: "{{ route('admin.books.index') }}",
                // isi td dari tablenya
                columns: [
                    // data dan name: nama kolom, searchable: bisa disearch gak datanya, orderable: bisa diurutin gak datanya
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'coverImg',
                        name: 'coverImg',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    // data : dari nama field/addcolumn/editcolumn
                    // name : bookCategory.name : untuk search/sort carinya dari relasi
                    {
                        data: 'book_category_id',
                        name: 'bookCategory.name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ]
            });
            // proses memunculkan modal ketika btn detail di klik
            $('#data-buku').on('click', '#btn-detail', function() {
                const btn = $(this);
                //ambil data attribut data-title="" yg dikirim di datatavle controller
                let title = btn.data('title');
                let category = btn.data('category');
                let price = "Rp" + btn.data('price');
                let publisher = btn.data('publisher');
                let writer = btn.data('writer');
                let language = btn.data('language');
                let pageOfBook = btn.data('pageOfBook');
                let releaseDate = btn.data('releaseDate');
                let description = btn.data('description');

                //kirim data ke modal
                $('#data-title').text(title);
                $('#data-category').text(category);
                $('#data-price').text(price);
                $('#data-publisher').text(publisher);
                $('#data-writer').text(writer);
                $('#data-language').text(language);
                $('#data-page-of-book').text(pageOfBook);
                $('#data-release-date').text(releaseDate);

                $('#data-description').html(description);

                // isi src ganbar di modal dengan data cover yg dikirim di datatable controller
                let cover = btn.data('cover');
                $('#data-cover').attr('src', cover);

                // panggil modal, munculkan
                $('#modal-blurred').modal('show');
            })
        });
    </script>
@endpush
