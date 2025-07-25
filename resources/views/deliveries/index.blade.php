@extends('layouts.auth')

@section('content')
<div class="row ">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Deliveries</h4>
                    <a type="button" class="btn btn-success btn-icon-text" href="{{ route('deliveries.create') }}">
                        <i class="mdi mdi-plus"></i> Add Delivery
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                {{-- <th>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </th> --}}
                                <th> Delivered At </th>
                                <th> Delivery Code </th>
                                <th> Courier Name </th>
                                <th> Status </th>
                                <th> Schedule At </th>
                                <th> Notes </th>
                                {{-- <th> Created At </th>
                                <th> Updated At </th> --}}
                                <th> Action </th>
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
                                {{-- <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input">
                                        </label>
                                    </div>
                                </td> --}}
                                <td> {{ $delivery->scheduled_at }} </td>
                                <td>
                                    <a href="{{ route('deliveries.show', $delivery->id) }}"
                                        class="btn btn-sm btn-dark">{{ $delivery->kode_pengiriman }}</a>
                                </td>
                                <td>{{ $delivery->courier_name }}</td>
                                <td>
                                    <label class="badge {{ $statusClass }}">{{ ucfirst($delivery->status) }}</label>
                                </td>
                                <td> {{ $delivery->delivered_at }} </td>
                                <td> {{ $delivery->notes }} </td>
                                {{-- <td> {{ $delivery->created_at }} </td>
                                <td> {{ $delivery->updated_at }} </td> --}}
                                <td>
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                        action="{{ route('deliveries.destroy', $delivery->id) }}" method="POST">
                                        <a href="{{ route('deliveries.edit', $delivery->id) }}"
                                            class="btn btn-sm btn-primary">EDIT</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Pengiriman belum ada.
                            </div>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection