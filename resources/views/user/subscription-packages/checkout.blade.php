@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <h3>Pembayaran</h3>
        <div class="row mt-3">
            <!-- section untuk QRcode -->
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Scan QR Code</h3>
                        <p class="text-center text-secondary">Gunakan aplikasi e-wallet atau m-banking anda untuk memindai kode di bawah ini</p>
                        <div class="d-flex justify-content-center">
                            {!! QrCode::size(250)->generate($subscriptionPackage->id); !!}
                        </div>
                    </div>
                </div>
            </div>  
            <!-- section untuk detail pembayaran -->
            <div class="col-4 px-2">
                <div class="card">
                    <form method="post" action="{{ route('subscription-package.checkout', $subscriptionPackage->id) }}" class="card-body">
                        @csrf
                        <h3>Detail Pembelian</h3>
                        <div class="d-flex justify-content-between ps-2">
                            <p>Total Item</p>
                            <p class="text-secondary">1</p>
                        </div>
                        <h4>Sub Total</h4>
                        <div class="d-flex justify-content-between ps-2">
                            <p>Total Harga</p>
                            <p class="text-secondary">Rp. {{ number_format($subscriptionPackage->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="d-flex justify-content-between ps-2 text-danger">
                            <p>Total Diskon</p>
                            <p class="text-secondary">Rp. {{ number_format(($subscriptionPackage->price * 0.2), 0, ',', '.') }}</p>
                        </div>
                        <div class="d-flex justify-content-between ps-2">
                            <p>Total Bayar</p>
                            <p class="text-secondary">Rp. {{ number_format($subscriptionPackage->price, 0, ',', '.') }}</p>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill mt-2 w-100">Bayar Sekarang</button>
                    </form>
                </div>
            </div>    
        </div>
    </div>

@endsection