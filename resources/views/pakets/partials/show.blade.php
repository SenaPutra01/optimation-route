@extends('layouts.auth')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Detail Paket</h4>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kode Paket</label>
                    <input type="text" class="form-control bg-dark text-light" value="{{ $paket->kode_paket }}"
                        readonly>
                </div>
                <div class="form-group">
                    <label>Nama Penerima</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->detail->nama_penerima ?? '-' }}">
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->detail->alamat_lengkap ?? '-' }}">
                </div>
                <div class="form-group">
                    <label>Jenis Barang</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->detail->jenis_barang ?? '-' }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Berat (kg)</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->detail->berat ?? '-' }}">
                </div>
                <div class="form-group">
                    <label>Tanggal Pengiriman</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->tanggal_pengiriman ?? '-' }}">
                </div>
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->location->lat ?? '-' }}">
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" class="form-control bg-dark text-light"
                        value="{{ $paket->location->lng ?? '-' }}">
                </div>
            </div>
        </div>

        <a href="{{ route('pakets.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection