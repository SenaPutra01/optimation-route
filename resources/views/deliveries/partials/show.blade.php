@extends('layouts.auth')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Detail Pengiriman</h4>
                <div class="row">
                    <div class="col-4 table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Kode Pengiriman</th>
                                    <td>{{ $delivery->kode_pengiriman }}</td>
                                </tr>
                                <tr>
                                    <th>Kurir</th>
                                    <td>{{ $delivery->courier_name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge bg-primary text-white">{{ ucfirst($delivery->status)
                                            }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jadwal Pengiriman</th>
                                    <td>{{ $delivery->scheduled_at->format('d M Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <h5>Daftar Paket</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Kode Paket</th>
                                <th>Nama Penerima</th>
                                <th>Alamat Lengkap</th>
                                <th>Jenis Barang</th>
                                <th>Berat (kg)</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($delivery->details as $item)
                            @php $paket = $item->paket; @endphp
                            <tr>
                                <td>{{ $paket->kode_paket }}</td>
                                <td>{{ optional($paket->detail)->nama_penerima ?? '-' }}</td>
                                <td>{{ optional($paket->detail)->alamat_lengkap ?? '-' }}</td>
                                <td>{{ optional($paket->detail)->jenis_barang ?? '-' }}</td>
                                <td>{{ optional($paket->detail)->berat ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('pakets.show', $paket->id) }}"
                                        class="btn btn-sm btn-dark">SHOW</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada paket dalam pengiriman ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button id="generateRouteBtn" class="btn btn-primary">
                        <i class="mdi mdi-map-marker-path"></i> Generate Rute Pengiriman
                    </button>
                </div>
                <div class="mt-4">
                    <h5 class="mb-3 mt-4">Peta Rute</h5>

                    <div id="routeMap" style="height: 800px; width: 100%; border-radius: 8px;"></div>
                </div>


                <div class="row">
                    <div class="col-md-5">
                        <div class="details" id="details"></div>
                        <div id="unreachableListContainer" class="mt-3"></div>
                        <div id="segmentDetails"></div>
                        <div id="googleMapsLinkContainer" class="mb-3 mt-4"></div>
                    </div>
                    <div class="col-md-7">
                    </div>
                </div>


                <div class="mt-3">
                    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<style>
    #routeMap {
        height: 400px;
        width: 100%;
        min-height: 300px;
        border-radius: 8px;
        position: relative;
        display: block;
        z-index: 1;
    }

    .leaflet-routing-container {
        color: #000000;
        font-family: 'Arial', sans-serif;
        font-size: 12px;
    }

    .leaflet-routing-container a {
        color: #007bff;
    }

    .leaflet-routing-container-wrapper {
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease;
    }

    .leaflet-routing-container-wrapper.hidden {
        max-height: 0;
        opacity: 0;
        pointer-events: none;
    }

    .leaflet-routing-container-wrapper.visible {
        max-height: 500px;
        opacity: 1;
    }

    .custom-div-icon {
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
    }
</style>
@endpush


@push('script')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let map;
    let routeLines = [];

    function generateSegmentColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = Math.floor((360 / count) * i);
            colors.push(`hsl(${hue}, 100%, 50%)`);
        }
        return colors;
    }

    let segmentColors = [];

    function getColoredMarker(i, name) {
        const color = segmentColors[i % segmentColors.length];
        return L.marker([0, 0], {
            zIndexOffset: 1000,
            icon: L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 500; color: black;">
                        <i class="mdi mdi-map-marker" style="color:${color}; font-size: 24px;"></i> ${name}
                    </span>
                `,
                iconSize: [40, 42],
                iconAnchor: [20, 42],
            })
        });
    }

    async function displayRoute(routeDetails) {
        segmentColors = generateSegmentColors(routeDetails.length);

        if (!map) {
            const firstCoord = routeDetails[0].path_coordinates[0];
            map = L.map('routeMap').setView(firstCoord, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © OpenStreetMap contributors'
            }).addTo(map);
        }

        routeLines.forEach(line => map.removeLayer(line));
        routeLines = [];
        map.eachLayer(layer => {
            if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                if (!layer._url) map.removeLayer(layer);
            }
        });

        let segmentDetailHTML = '<div class="mt-3"><strong>Detail Segment:</strong><ul style="font-size: 14px;">';
        let totalDistance = 0;
        let totalDuration = 0;
        let fuelCostPerKm = 2500;
        let totalFuelCost = 0;

        routeDetails.forEach((segment, i) => {
            const color = segmentColors[i];
            const coords = segment.path_coordinates;

            const line = L.polyline(coords, {
                color: color,
                weight: 5,
                opacity: 0.9
            }).addTo(map).bindPopup(
                `<b>${segment.from} ➝ ${segment.to}</b><br>` +
                `Jarak: ${segment.distance_km.toFixed(2)} km<br>` +
                `Durasi: ${segment.duration_min.toFixed(1)} menit`
            );

            routeLines.push(line);
            totalDistance += segment.distance_km;
            totalDuration += segment.duration_min;
            totalFuelCost += segment.distance_km * fuelCostPerKm;

            segmentDetailHTML += `<li style="color:${color};">${segment.from} ➝ ${segment.to}: ` +
                `${segment.distance_km.toFixed(2)} km, ${segment.duration_min.toFixed(1)} menit</li>`;
        });

        segmentDetailHTML += `</ul><hr><div style="font-size: 14px;"><strong>Total Jarak:</strong> ${totalDistance.toFixed(2)} km<br>` +
            `<strong>Total Durasi:</strong> ${totalDuration.toFixed(1)} menit<br>` +
            `<strong>Tarif per Km:</strong> Rp ${fuelCostPerKm.toLocaleString()}<br>` +
            `<strong>Perhitungan:</strong> ${totalDistance.toFixed(2)} km × Rp ${fuelCostPerKm.toLocaleString()}<br>` +
            `<strong>Estimasi Biaya Bensin:</strong> Rp ${Math.round(totalFuelCost).toLocaleString()}</div></div>`;
        document.getElementById('segmentDetails').innerHTML = segmentDetailHTML;

        // Marker
        const visited = new Set();
        routeDetails.forEach((segment, i) => {
            const fromCoord = segment.path_coordinates[0];
            const toCoord = segment.path_coordinates[segment.path_coordinates.length - 1];

            if (!visited.has(segment.from)) {
                getColoredMarker(i, segment.from).setLatLng(fromCoord).addTo(map);
                visited.add(segment.from);
            }
            if (!visited.has(segment.to)) {
                getColoredMarker(i + 1, segment.to).setLatLng(toCoord).addTo(map);
                visited.add(segment.to);
            }
        });

        map.fitBounds(L.featureGroup(routeLines).getBounds(), {
            padding: [50, 50],
            maxZoom: 17
        });

        if (!document.getElementById('routeLegend')) {
            const legend = document.createElement('div');
            legend.id = 'routeLegend';
            legend.className = 'leaflet-control leaflet-bar';
            legend.style.padding = '10px';
            legend.style.background = 'white';
            legend.innerHTML = `
                <strong>Legenda Warna Rute:</strong>
                <ul style="margin: 5px 0; padding-left: 15px; font-size: 12px;">
                    ${routeDetails.map((r, i) => {
                        const color = segmentColors[i];
                        return `<li style="color:${color};"><span style="display:inline-block;width:12px;height:12px;background:${color};margin-right:4px;border-radius:2px"></span>${r.from} ➝ ${r.to}</li>`;
                    }).join('')}
                </ul>`;
            const customControl = L.control({ position: 'bottomleft' });
            customControl.onAdd = () => legend;
            customControl.addTo(map);
        }
    }

    // async function displayRoute(routeDetails) {
    //     segmentColors = generateSegmentColors(routeDetails.length);

    //     if (!map) {
    //         const firstCoord = routeDetails[0].path_coordinates[0];
    //         map = L.map('routeMap').setView(firstCoord, 13);
    //         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //             attribution: 'Map data © OpenStreetMap contributors'
    //         }).addTo(map);
    //     }

    //     routeLines.forEach(line => map.removeLayer(line));
    //     routeLines = [];
    //     map.eachLayer(layer => {
    //         if (layer instanceof L.Marker || layer instanceof L.Polyline) {
    //             if (!layer._url) map.removeLayer(layer);
    //         }
    //     });

    //     let segmentDetailHTML = '<div class="mt-3"><strong>Detail Segment:</strong><ul style="font-size: 14px;">';
    //     let totalDistance = 0;
    //     let totalDuration = 0;
    //     let fuelCostPerKm = 2500;
    //     let totalFuelCost = 0;

    //     routeDetails.forEach((segment, i) => {
    //         const color = segmentColors[i];
    //         const fromCoord = segment.path_coordinates[0];
    //         const toCoord = segment.path_coordinates[segment.path_coordinates.length - 1];
    //         const coords = [fromCoord, toCoord]; // Hanya garis lurus

    //         const line = L.polyline(coords, {
    //             color: color,
    //             weight: 5,
    //             opacity: 0.9
    //         }).addTo(map).bindPopup(
    //             `<b>${segment.from} ➝ ${segment.to}</b><br>` +
    //             `Jarak: ${segment.distance_km.toFixed(2)} km<br>` +
    //             `Durasi: ${segment.duration_min.toFixed(1)} menit`
    //         );

    //         routeLines.push(line);
    //         totalDistance += segment.distance_km;
    //         totalDuration += segment.duration_min;
    //         totalFuelCost += segment.distance_km * fuelCostPerKm;

    //         segmentDetailHTML += `<li style="color:${color};">${segment.from} ➝ ${segment.to}: ` +
    //             `${segment.distance_km.toFixed(2)} km, ${segment.duration_min.toFixed(1)} menit</li>`;
    //     });

    //     segmentDetailHTML += `</ul><hr><div style="font-size: 14px;"><strong>Total Jarak:</strong> ${totalDistance.toFixed(2)} km<br>` +
    //         `<strong>Total Durasi:</strong> ${totalDuration.toFixed(1)} menit<br>` +
    //         `<strong>Tarif per Km:</strong> Rp ${fuelCostPerKm.toLocaleString()}<br>` +
    //         `<strong>Perhitungan:</strong> ${totalDistance.toFixed(2)} km × Rp ${fuelCostPerKm.toLocaleString()}<br>` +
    //         `<strong>Estimasi Biaya Bensin:</strong> Rp ${Math.round(totalFuelCost).toLocaleString()}</div></div>`;
    //     document.getElementById('segmentDetails').innerHTML = segmentDetailHTML;

    //     const visited = new Set();
    //     routeDetails.forEach((segment, i) => {
    //         const fromCoord = segment.path_coordinates[0];
    //         const toCoord = segment.path_coordinates[segment.path_coordinates.length - 1];

    //         if (!visited.has(segment.from)) {
    //             getColoredMarker(i, segment.from).setLatLng(fromCoord).addTo(map);
    //             visited.add(segment.from);
    //         }
    //         if (!visited.has(segment.to)) {
    //             getColoredMarker(i + 1, segment.to).setLatLng(toCoord).addTo(map);
    //             visited.add(segment.to);
    //         }
    //     });

    //     map.fitBounds(L.featureGroup(routeLines).getBounds(), {
    //         padding: [50, 50],
    //         maxZoom: 17
    //     });

    //     if (!document.getElementById('routeLegend')) {
    //         const legend = document.createElement('div');
    //         legend.id = 'routeLegend';
    //         legend.className = 'leaflet-control leaflet-bar';
    //         legend.style.padding = '10px';
    //         legend.style.background = 'white';
    //         legend.innerHTML = `
    //             <strong>Legenda Warna Rute:</strong>
    //             <ul style="margin: 5px 0; padding-left: 15px; font-size: 12px;">
    //                 ${routeDetails.map((r, i) => {
    //                     const color = segmentColors[i];
    //                     return `<li style="color:${color};"><span style="display:inline-block;width:12px;height:12px;background:${color};margin-right:4px;border-radius:2px"></span>${r.from} ➝ ${r.to}</li>`;
    //                 }).join('')}
    //             </ul>`;
    //         const customControl = L.control({ position: 'bottomleft' });
    //         customControl.onAdd = () => legend;
    //         customControl.addTo(map);
    //     }
    // }


    // 🔹 On Page Load
    window.addEventListener('DOMContentLoaded', async function () {
        const routeDetails = @json($routeDetails);

        if (routeDetails.length > 0) {
            document.getElementById('generateRouteBtn').style.display = 'none';
            await displayRoute(routeDetails);
        } else {
            document.getElementById('generateRouteBtn').style.display = 'inline-block';
        }
    });

    // 🔹 Button Action
    document.getElementById('generateRouteBtn').addEventListener('click', async function () {
        const btn = this;
        btn.disabled = true;
        btn.innerText = 'Generating...';

        try {
            const res = await fetch("{{ route('deliveries.route', ['kodePengiriman' => $delivery->kode_pengiriman]) }}");
            const data = await res.json();

            if (data.route_details && data.route_details.length > 0) {
                await displayRoute(data.route_details);
                btn.style.display = 'none'; // sembunyikan tombol setelah sukses
            } else {
                alert('Tidak ada data rute ditemukan.');
            }

        } catch (err) {
            console.error(err);
            alert('Gagal mengambil data rute.');
        }

        btn.disabled = false;
        btn.innerText = 'Generate Rute Pengiriman';
    });
</script>

@endpush