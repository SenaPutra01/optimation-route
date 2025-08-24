@extends('layouts.auth')

@section('content')
<div class="row ">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Deliveries</h4>

                    @if(Auth::user()->role === 'admin')
                    <a type="button" class="btn btn-success btn-icon-text" href="{{ route('deliveries.create') }}">
                        <i class="mdi mdi-plus"></i> Add Delivery
                    </a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> Delivered At </th>
                                <th> Delivery Code </th>
                                <th> Courier Name </th>
                                <th> Status </th>
                                <th> Schedule At </th>
                                <th> Notes </th>
                                @if(Auth::user()->role === 'admin')
                                <th> Action </th>
                                @endif
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
                                @if(Auth::user()->role === 'admin')
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
                                @endif
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