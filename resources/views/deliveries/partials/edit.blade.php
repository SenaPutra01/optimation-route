@extends('layouts.auth')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Pengiriman</h4>

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('failed'))
                <div class="alert alert-danger">{{ session('failed') }}</div>
                @endif

                <form action="{{ route('deliveries.update', $delivery->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">

                            {{-- Pilih Kurir --}}
                            <div class="form-group">
                                <label for="courier_name">Pilih Kurir</label>
                                <select name="courier_name"
                                    class="form-control @error('courier_name') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('courier_name', $delivery->courier_name ?? '') ==
                                        '' ? 'selected' : '' }}>
                                        -- Pilih Kurir --
                                    </option>
                                    @foreach ($couriers as $courier)
                                    <option value="{{ $courier->name }}" {{ old('courier_name', $delivery->courier_name
                                        ?? '') == $courier->name ? 'selected' : '' }}>
                                        {{ $courier->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('courier_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Scan Barcode --}}
                            <div class="form-group mt-4">
                                <label>Scan Barcode</label>
                                <button type="button" id="toggleScannerBtn" onclick="toggleScanner()"
                                    class="btn btn-info d-flex align-items-center px-4 py-2">
                                    <i class="mdi mdi-barcode-scan" style="font-size: 1.8rem; margin-right: 8px;"></i>
                                    <span class="fw-bold">Scan Paket</span>
                                </button>
                            </div>

                            {{-- Pilih Paket --}}
                            <div class="form-group">
                                <label for="paket_ids">Pilih Paket</label>
                                @php
                                $assignedPaketIds = $delivery->pakets->pluck('id')->toArray();
                                @endphp
                                <div class="form-check">
                                    @foreach ($pakets as $paket)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="paket_ids[]"
                                            value="{{ $paket->id }}" id="paket_{{ $paket->id }}" {{ in_array($paket->id,
                                        old('paket_ids', $assignedPaketIds)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="paket_{{ $paket->id }}">
                                            {{ $paket->kode_paket }} - {{ optional($paket->detail)->nama_penerima }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('paket_ids')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('deliveries.index') }}" class="btn btn-light">Cancel</a>
                        </div>

                        {{-- Scanner Area --}}
                        <div class="col-md-6 d-flex justify-content-center align-items-center"
                            style="min-height: 400px;">
                            <div id="reader" style="width: 400px; max-width: 100%; border-radius: 8px;"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let html5QrCode;
let isScanning = false;

window.toggleScanner = function () {
    const scannerId = "reader";
    const btn = document.getElementById("toggleScannerBtn");
    const btnIcon = btn.querySelector("i");
    const btnText = btn.querySelector("span");

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode(scannerId);
    }

    if (!isScanning) {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            (decodedText) => {
                const checkbox = document.querySelector(`input[type="checkbox"][value="${decodedText}"]`);
                const alertContainer = document.getElementById("scan-alert-container");
                alertContainer.innerHTML = '';

                if (checkbox) {
                    checkbox.checked = true;
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success';
                    successAlert.textContent = 'Paket berhasil discan: ' + decodedText;
                    alertContainer.appendChild(successAlert);
                    setTimeout(() => successAlert.remove(), 3000);
                } else {
                    alert("Paket tidak ditemukan: " + decodedText);
                }
            },
            (err) => console.warn("Scan error:", err)
        ).then(() => {
            isScanning = true;
            btnIcon.className = "mdi mdi-close-circle-outline";
            btnText.innerText = "Tutup Kamera";
        }).catch((err) => console.error("Gagal memulai scanner:", err));
    } else {
        html5QrCode.stop().then(() => {
            isScanning = false;
            btnIcon.className = "mdi mdi-barcode-scan";
            btnText.innerText = "Buka Kamera";
        }).catch((err) => console.error("Gagal menghentikan scanner:", err));
    }
};
</script>
@endpush