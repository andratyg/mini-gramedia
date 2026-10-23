@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between">
            <h3>Data Buku yang akan Dipilih untuk paket langganan <b>{{ $subscriptionPackage->name }}</b></h3>
        </div>
        <form action="{{ route('admin.subscription-package.books.store', $subscriptionPackage->id) }}" method="POST" id="addBookForm">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Daftar Buku</span>
                   
                    <button type="submit" id="btnSubmitBooks" class="btn btn-primary d-none">
                        <i class="fas fa-plus me-1"></i>Tambahkan ke paket langganan (<span id="selectedCount">0</span>)
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mt-3" id="data-buku">
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Sampul Buku</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </form>
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
                ajax: "{{ route('admin.subscription-package.books', $subscriptionPackage->id) }}",
                // isi td dari tablenya
                columns: [
                    {   
                        //yg dikirimkan ketika  ceklis buku itu idnya karena yg diperlukan subscriptionbook itu book_id
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        //sama kayak editcolumn di serverside
                        render: function(data, type, row){
                            // jika buku sudah pernah ditambahkan ke paket langganan ini
                            if(row.checkAdded === 'Ya'){
                                return ` `;
                            }
                            //(data) idisnya dari data:'id'
                            //name pake[] karena yang diceklis bisa lebih dari 1 (array)
                            return `<input type="checkbox" value="${data}" name="book_id[]" class="book-checkbox">`;
                            //class book-checkbox ditambahkan untuk mendeteksi jumlah checkbox yg diceklis nantinya
                        }
                    },
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
                    }

                ]
            });
        });

        function updateButtonVisibility() {
            let checkedCount = $('.book-checkbox:checked').length;
            $('#selectedCount').text(checkedCount);

            if(checkedCount > 0) {
                $('#btnSubmitBooks').removeClass('d-none');
            } else {
                $('#btnSubmitBooks').addClass('d-none');
            }
        }
        // event listener saat checkbox
        $('#data-buku tbody').on('change', '.book-checkbox', function() {
            updateButtonVisibility();
        });
    </script>
@endpush
