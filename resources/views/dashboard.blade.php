@extends('layouts.auth')

@section('content')
<div class="row">
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ $totalPaket }}</h3>
                            {{-- <p class="text-success ml-2 mb-0 font-weight-medium">+3.5%</p> --}}
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-success ">
                            <span class="mdi mdi-package icon-item"></span>
                        </div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Total Paket</h6>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ $totalDelivered }}</h3>
                            {{-- <p class="text-success ml-2 mb-0 font-weight-medium">+11%</p> --}}
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-success">
                            <span class="mdi mdi-truck-delivery icon-item"></span>
                        </div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Total Paket Dikirim</h6>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ $totalPending }}</h3>
                            {{-- <p class="text-danger ml-2 mb-0 font-weight-medium">-2.4%</p> --}}
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-danger">
                            <span class="mdi mdi-package icon-item"></span>
                        </div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Total Paket Baru</h6>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ $totalDelivery }}</h3>
                            {{-- <p class="text-success ml-2 mb-0 font-weight-medium">+3.5%</p> --}}
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-success ">
                            <span class="mdi mdi-truck-delivery icon-item"></span>
                        </div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Total Pengiriman</h6>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Persentase Pengiriman</h4>
                <canvas id="transaction-history" class="transaction-chart"></canvas>
                {{-- <div
                    class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                    <div class="text-md-center text-xl-left">
                        <h6 class="mb-1">Transfer to Paypal</h6>
                        <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                    </div>
                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                        <h6 class="font-weight-bold mb-0">$236</h6>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                    <h4 class="card-title mb-1">Pengiriman Terbaru</h4>
                    <p class="text-muted mb-1">Status Pengiriman</p>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="preview-list">
                            @forelse ($recentDeliveries as $delivery)
                            <div class="preview-item border-bottom">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-primary">
                                        <i class="mdi mdi-truck-delivery"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-sm-flex flex-grow">
                                    <div class="flex-grow">
                                        <h6 class="preview-subject">Kode: {{ $delivery->kode_pengiriman }}</h6>
                                        <p class="text-muted mb-0">Kurir: {{ $delivery->courier_name }}</p>
                                    </div>
                                    <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                        <p class="text-muted">
                                            {{ $delivery->created_at->diffForHumans() }}
                                        </p>
                                        <p class="text-muted mb-0">Status: <span
                                                class="badge badge-outline-{{ $delivery->status === 'pending' ? 'warning' : 'success' }}">{{
                                                ucfirst($delivery->status) }}</span></p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="preview-item border-bottom">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-primary">
                                        <i class="mdi mdi-truck-delivery"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-sm-flex flex-grow">
                                    <div class="flex-grow">
                                        {{-- <h6 class="preview-subject">Kode: {{ $delivery->kode_pengiriman }}</h6>
                                        --}}
                                        <p class="text-muted mb-0"> Tidak ada pengiriman dalam 3 jam terakhir.</p>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="row ">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Status Pengiriman</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> Delivery Code </th>
                                <th> Courier Name </th>
                                <th> Status </th>
                                <th> Schedule At </th>
                                <th> Delivered At </th>
                                <th> Notes </th>
                                <th> Created At </th>
                                <th> Updated At </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deliveries as $delivery)
                            @php
                            $statusClass = [
                            'Pending' => 'badge-danger',
                            'Delivered' => 'badge-warning',
                            'Received' => 'badge-success',
                            ][$delivery->status] ?? 'badge-secondary';
                            @endphp

                            <tr>
                                <td>
                                    {{ $delivery->kode_pengiriman }}
                                </td>
                                <td>{{ $delivery->courier_name }}</td>
                                <td>
                                    <label class="badge {{ $statusClass }}">{{ ucfirst($delivery->status) }}</label>
                                </td>
                                <td> {{ $delivery->scheduled_at }} </td>
                                <td> {{ $delivery->delivered_at }} </td>
                                <td> {{ $delivery->notes }} </td>
                                <td> {{ $delivery->created_at }} </td>
                                <td> {{ $delivery->updated_at }} </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Pengiriman belum ada.
                            </div>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                {{-- <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </th>
                                <th> Client Name </th>
                                <th> Order No </th>
                                <th> Product Cost </th>
                                <th> Project </th>
                                <th> Payment Mode </th>
                                <th> Start Date </th>
                                <th> Payment Status </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ Vite::asset('resources/assets/images/faces/face1.jpg') }}"
                                        alt="image" />
                                    <span class="pl-2">Henry Klein</span>
                                </td>
                                <td> 02312 </td>
                                <td> $14,500 </td>
                                <td> Dashboard </td>
                                <td> Credit card </td>
                                <td> 04 Dec 2019 </td>
                                <td>
                                    <div class="badge badge-outline-success">Approved</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ Vite::asset('resources/assets/images/faces/face2.jpg') }}"
                                        alt="image" />
                                    <span class="pl-2">Estella Bryan</span>
                                </td>
                                <td> 02312 </td>
                                <td> $14,500 </td>
                                <td> Website </td>
                                <td> Cash on delivered </td>
                                <td> 04 Dec 2019 </td>
                                <td>
                                    <div class="badge badge-outline-warning">Pending</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ Vite::asset('resources/assets/images/faces/face5.jpg') }}"
                                        alt="image" />
                                    <span class="pl-2">Lucy Abbott</span>
                                </td>
                                <td> 02312 </td>
                                <td> $14,500 </td>
                                <td> App design </td>
                                <td> Credit card </td>
                                <td> 04 Dec 2019 </td>
                                <td>
                                    <div class="badge badge-outline-danger">Rejected</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ Vite::asset('resources/assets/images/faces/face3.jpg') }}"
                                        alt="image" />
                                    <span class="pl-2">Peter Gill</span>
                                </td>
                                <td> 02312 </td>
                                <td> $14,500 </td>
                                <td> Development </td>
                                <td> Online Payment </td>
                                <td> 04 Dec 2019 </td>
                                <td>
                                    <div class="badge badge-outline-success">Approved</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ Vite::asset('resources/assets/images/faces/face4.jpg') }}"
                                        alt="image" />
                                    <span class="pl-2">Sallie Reyes</span>
                                </td>
                                <td> 02312 </td>
                                <td> $14,500 </td>
                                <td> Website </td>
                                <td> Credit card </td>
                                <td> 04 Dec 2019 </td>
                                <td>
                                    <div class="badge badge-outline-success">Approved</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Visitors by Countries</h4>
                <div class="row">
                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-us"></i>
                                        </td>
                                        <td>USA</td>
                                        <td class="text-right"> 1500 </td>
                                        <td class="text-right font-weight-medium"> 56.35% </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-de"></i>
                                        </td>
                                        <td>Germany</td>
                                        <td class="text-right"> 800 </td>
                                        <td class="text-right font-weight-medium"> 33.25% </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-au"></i>
                                        </td>
                                        <td>Australia</td>
                                        <td class="text-right"> 760 </td>
                                        <td class="text-right font-weight-medium"> 15.45% </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-gb"></i>
                                        </td>
                                        <td>United Kingdom</td>
                                        <td class="text-right"> 450 </td>
                                        <td class="text-right font-weight-medium"> 25.00% </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-ro"></i>
                                        </td>
                                        <td>Romania</td>
                                        <td class="text-right"> 620 </td>
                                        <td class="text-right font-weight-medium"> 10.25% </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-br"></i>
                                        </td>
                                        <td>Brasil</td>
                                        <td class="text-right"> 230 </td>
                                        <td class="text-right font-weight-medium"> 75.00% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div id="audience-map" class="vector-map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@push('script')
{{-- <script src="{{ Vite::asset('resources/assets/js/dashboard.js') }}"></script> --}}
<script>
    (function ($) {
    "use strict";
    $.fn.andSelf = function () {
        return this.addBack.apply(this, arguments);
    };
    $(function () {
        const totalPaket = {{ $totalPaket }};
        const totalDelivered = {{ $totalDelivered }};
        const totalPending = {{ $totalPending }};

        const totalAll = totalDelivered + totalPending; // sama dengan totalPaket
        const percent = totalAll > 0 ? parseFloat(((totalDelivered / totalAll) * 100).toFixed(2)) : 0;


        if ($("#currentBalanceCircle").length) {
            var bar = new ProgressBar.Circle(currentBalanceCircle, {
                color: "#000",
                // This has to be the same size as the maximum width to
                // prevent clipping
                strokeWidth: 12,
                trailWidth: 12,
                trailColor: "#0d0d0d",
                easing: "easeInOut",
                duration: 1400,
                text: {
                    autoStyleContainer: false,
                },
                from: { color: "#d53f3a", width: 12 },
                to: { color: "#d53f3a", width: 12 },
                // Set default step function for all animate calls
                step: function (state, circle) {
                    circle.path.setAttribute("stroke", state.color);
                    circle.path.setAttribute("stroke-width", state.width);

                    var value = Math.round(circle.value() * 100);
                    circle.setText("");
                },
            });

            bar.text.style.fontSize = "1.5rem";
            bar.animate(0.4); // Number from 0.0 to 1.0
        }
        if ($("#audience-map").length) {
            $("#audience-map").vectorMap({
                map: "world_mill_en",
                backgroundColor: "transparent",
                panOnDrag: true,
                focusOn: {
                    x: 0.5,
                    y: 0.5,
                    scale: 1,
                    animate: true,
                },
                series: {
                    regions: [
                        {
                            scale: ["#3d3c3c", "#f2f2f2"],
                            normalizeFunction: "polynomial",
                            values: {
                                BZ: 75.0,
                                US: 56.25,
                                AU: 15.45,
                                GB: 25.0,
                                RO: 10.25,
                                GE: 33.25,
                            },
                        },
                    ],
                },
            });
        }
        if ($("#transaction-history").length) {
            var areaData = {
                labels: ["Total Paket", "Total Tekirim", "Total Paket Baru"],
                datasets: [
                    {
                        data: [totalPaket, totalDelivered, totalPending],
                        backgroundColor: ["#111111", "#00d25b", "#ffab00"],
                    },
                ],
            };
            var areaOptions = {
                responsive: true,
                maintainAspectRatio: true,
                segmentShowStroke: false,
                cutoutPercentage: 70,
                elements: {
                    arc: {
                        borderWidth: 0,
                    },
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    enabled: true,
                },
            };
            var transactionhistoryChartPlugins = {
                beforeDraw: function (chart) {
                    var width = chart.chart.width,
                        height = chart.chart.height,
                        ctx = chart.chart.ctx;

                    ctx.restore();
                    var fontSize = 1;
                    ctx.font = fontSize + "rem sans-serif";
                    ctx.textAlign = "left";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#ffffff";

                    var text = percent + ' %',
                        textX = Math.round(
                            (width - ctx.measureText(text).width) / 2
                        ),
                        textY = height / 2.4;

                    ctx.fillText(text, textX, textY);

                    ctx.restore();
                    var fontSize = 0.75;
                    ctx.font = fontSize + "rem sans-serif";
                    ctx.textAlign = "left";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#6c7293";

                    var texts = "Total",
                        textsX = Math.round(
                            (width - ctx.measureText(text).width) / 1.93
                        ),
                        textsY = height / 1.7;

                    ctx.fillText(texts, textsX, textsY);
                    ctx.save();
                },
            };
            var transactionhistoryChartCanvas = $("#transaction-history")
                .get(0)
                .getContext("2d");
            var transactionhistoryChart = new Chart(
                transactionhistoryChartCanvas,
                {
                    type: "doughnut",
                    data: areaData,
                    options: areaOptions,
                    plugins: transactionhistoryChartPlugins,
                }
            );
        }
        if ($("#transaction-history-arabic").length) {
            var areaData = {
                labels: ["Paypal", "Stripe", "Cash"],
                datasets: [
                    {
                        data: [55, 25, 20],
                        backgroundColor: ["#111111", "#00d25b", "#ffab00"],
                    },
                ],
            };
            var areaOptions = {
                responsive: true,
                maintainAspectRatio: true,
                segmentShowStroke: false,
                cutoutPercentage: 70,
                elements: {
                    arc: {
                        borderWidth: 0,
                    },
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    enabled: true,
                },
            };
            var transactionhistoryChartPlugins = {
                beforeDraw: function (chart) {
                    var width = chart.chart.width,
                        height = chart.chart.height,
                        ctx = chart.chart.ctx;

                    ctx.restore();
                    var fontSize = 1;
                    ctx.font = fontSize + "rem sans-serif";
                    ctx.textAlign = "left";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#ffffff";

                    var text = "$1200",
                        textX = Math.round(
                            (width - ctx.measureText(text).width) / 2
                        ),
                        textY = height / 2.4;

                    ctx.fillText(text, textX, textY);

                    ctx.restore();
                    var fontSize = 0.75;
                    ctx.font = fontSize + "rem sans-serif";
                    ctx.textAlign = "left";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#6c7293";

                    var texts = "مجموع",
                        textsX = Math.round(
                            (width - ctx.measureText(text).width) / 1.93
                        ),
                        textsY = height / 1.7;

                    ctx.fillText(texts, textsX, textsY);
                    ctx.save();
                },
            };
            var transactionhistoryChartCanvas = $("#transaction-history-arabic")
                .get(0)
                .getContext("2d");
            var transactionhistoryChart = new Chart(
                transactionhistoryChartCanvas,
                {
                    type: "doughnut",
                    data: areaData,
                    options: areaOptions,
                    plugins: transactionhistoryChartPlugins,
                }
            );
        }
        if ($("#owl-carousel-basic").length) {
            $("#owl-carousel-basic").owlCarousel({
                loop: true,
                margin: 10,
                dots: false,
                nav: true,
                autoplay: true,
                autoplayTimeout: 4500,
                navText: [
                    "<i class='mdi mdi-chevron-left'></i>",
                    "<i class='mdi mdi-chevron-right'></i>",
                ],
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: 1,
                    },
                    1000: {
                        items: 1,
                    },
                },
            });
        }
        var isrtl = $("body").hasClass("rtl");
        if ($("#owl-carousel-rtl").length) {
            $("#owl-carousel-rtl").owlCarousel({
                loop: true,
                margin: 10,
                dots: false,
                nav: true,
                rtl: isrtl,
                autoplay: true,
                autoplayTimeout: 4500,
                navText: [
                    "<i class='mdi mdi-chevron-right'></i>",
                    "<i class='mdi mdi-chevron-left'></i>",
                ],
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: 1,
                    },
                    1000: {
                        items: 1,
                    },
                },
            });
        }
    });
})(jQuery);

</script>
@endpush