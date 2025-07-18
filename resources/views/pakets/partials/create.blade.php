@extends('layouts.auth')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Add New Paket</h4>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form class="form-sample" action="{{ route('pakets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <p class="card-description"> Paket Details </p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Receiver's Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nama_penerima') is-invalid @enderror"
                                name="nama_penerima" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('alamat_lengkap') is-invalid @enderror"
                                name="alamat_lengkap" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Category</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('jenis_barang') is-invalid @enderror"
                                name="jenis_barang" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Delivery Date</label>
                        <div class="col-sm-9">
                            <input class="form-control @error('tanggal_pengiriman') is-invalid @enderror"
                                name="tanggal_pengiriman" placeholder="dd/mm/yyyy" type="date" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Weight</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control @error('berat') is-invalid @enderror"
                                name="berat" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>
            <p class="card-description"> Coordinat </p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Latitude</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('lat') is-invalid @enderror" name="lat" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label">State</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" />
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Longitude</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('lng') is-invalid @enderror" name="lng" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Postcode</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" />
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label">City</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" />
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-6">
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Country</label>
                        <div class="col-sm-9">
                            <select class="form-control">
                                <option>America</option>
                                <option>Italy</option>
                                <option>Russia</option>
                                <option>Britain</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <a class="btn btn-dark" href="{{ route('pakets.index') }}">Cancel</a>
        </form>
    </div>
</div>
@endsection