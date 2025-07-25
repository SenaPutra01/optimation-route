@extends('layouts.auth')

@section('content')
<div class="row ">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Users</h4>
                    <a type="button" class="btn btn-success btn-icon-text" href="{{ route('pakets.create') }}">
                        <i class="mdi mdi-plus"></i> Add User
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> User Name </th>
                                <th> Role </th>
                                <th> Status </th>
                                {{-- <th> Status </th> --}}
                                <th> Action </th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @forelse ($pakets as $paket)

                            @php
                            $statusClass = [
                            'Pending' => 'badge-danger',
                            'Delivered' => 'badge-warning',
                            'Received' => 'badge-success',
                            ][$paket->status] ?? 'badge-secondary';
                            @endphp

                            <tr>
                                <td>
                                    {{ $paket->tanggal_pengiriman }}
                                </td>
                                <td>
                                    <a href="{{ route('pakets.show', $paket->id) }}">{{
                                        $paket->kode_paket }}</a>
                                </td>
                                <td> {{ $paket->detail->nama_penerima }} </td>
                                <td>
                                    <label class="badge {{ $statusClass }}">{{ ucfirst($paket->status) }}</label>
                                </td>
                                <td>
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                        action="{{ route('pakets.destroy', $paket->id) }}" method="POST">
                                        <a href="{{ route('pakets.edit', $paket->id) }}"
                                            class="btn btn-sm btn-primary">EDIT</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Paket belum ada.
                            </div>
                            @endforelse

                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection