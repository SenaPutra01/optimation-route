@extends('layouts.auth')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Users</h4>
                    <button type="button" class="btn btn-success btn-icon-text" data-bs-toggle="modal"
                        data-bs-target="#createUserModal">
                        <i class="mdi mdi-plus"></i> Add User
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            @php
                            $statusClass = [
                            'inactive' => 'badge-danger',
                            'active' => 'badge-success',
                            ][strtolower($user->status)] ?? 'badge-secondary';
                            @endphp

                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <label class="badge {{ $statusClass }}">{{ ucfirst($user->status) }}</label>
                                </td>
                                <td>
                                    <!-- Trigger Modal -->
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal-{{ $user->id }}">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form onsubmit="return confirm('Apakah Anda yakin?');"
                                        action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit User -->
                            <x-custom-modal :id="'editUserModal-' . $user->id"
                                :action="route('users.update', $user->id)" method="PUT" title="Edit User">
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                        placeholder="Name" required>
                                </div>

                                <div class="mb-3">
                                    <select class="form-control" name="role">
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="kurir" {{ $user->role === 'kurir' ? 'selected' : '' }}>Kurir
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <select class="form-control" name="status">
                                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : ''
                                            }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit" class="btn btn-success w-50">Save</button>
                                    <button type="button" class="btn btn-secondary w-50 me-2"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </x-custom-modal>

                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Data user belum tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <x-custom-modal id="createUserModal" :action="route('users.store')" method="POST"
                    title="Tambah User Baru">
                    {{-- Form inputan user --}}
                    <div class="mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>

                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="kurir">Kurir</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success w-50">Simpan</button>
                        <button type="button" class="btn btn-secondary w-50 me-2" data-bs-dismiss="modal">Batal</button>
                    </div>
                </x-custom-modal>


            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .custom-modal {
        background-color: #1b1d26;
        border-radius: 16px;
        border: none;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }

    .btn-pink {
        background-color: #d63384;
        color: white;
        border: none;
    }

    .btn-pink:hover {
        background-color: #c12372;
        color: white;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        height: 45px;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(214, 51, 132, 0.25);
        border-color: #d63384;
    }

    .modal-content input::placeholder {
        font-size: 14px;
    }
</style>
@endpush

@push('script')
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush