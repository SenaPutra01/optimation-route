@extends('layouts.auth')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Paket</h4>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form onsubmit="return confirm('Apakah Anda Yakin ?')" action=" {{ route('pakets.update', $paket->id) }}"
            method="POST">
            @csrf
            @method('PUT')
            <p class="card-description"> Paket Details </p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Paket</label>
                        <input type="text" name="kode_paket"
                            class="form-control @error('kode_paket') is-invalid @enderror"
                            value="{{ old('kode_paket', $paket->kode_paket) }}">
                    </div>
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="text" name="nama_penerima"
                            class="form-control @error('nama_penerima') is-invalid @enderror"
                            value="{{ old('nama_penerima', $paket->detail->nama_penerima ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <input type="text" name="alamat_lengkap"
                            class="form-control @error('alamat_lengkap') is-invalid @enderror"
                            value="{{ old('alamat_lengkap', $paket->detail->alamat_lengkap ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Jenis Barang</label>
                        <input type="text" name="jenis_barang"
                            class="form-control @error('jenis_barang') is-invalid @enderror"
                            value="{{ old('jenis_barang', $paket->detail->jenis_barang ?? '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Berat (kg)</label>
                        <input type="number" name="berat" class="form-control @error('berat') is-invalid @enderror"
                            value="{{ old('berat', $paket->detail->berat ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pengiriman</label>
                        <input type="date" name="tanggal_pengiriman"
                            class="form-control @error('tanggal_pengiriman') is-invalid @enderror"
                            value="{{ old('tanggal_pengiriman', $paket->tanggal_pengiriman) }}">
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror"
                            value="{{ old('lat', $paket->location->lat ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="lng" class="form-control @error('lng') is-invalid @enderror"
                            value="{{ old('lng', $paket->location->lng ?? '') }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pakets.index') }}" class="btn btn-dark">Cancel</a>
        </form>
    </div>
</div>
@endsection