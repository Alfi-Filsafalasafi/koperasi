    @extends('layouts.master')
    @section('title', 'Simpanan')
    @section('subtitle', 'Daftar')

    @section('simpanan', '') {{-- sesuaikan nama ini sesuai sidebar kalau ada --}}
    @section('content')
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body pt-4">
                    <a href="{{ route('simpanan.create') }}" class="btn btn-sm btn-success my-3">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Simpanan</th>
                                    <th>Nama Anggota</th>
                                    <th>Jenis Simpanan</th>
                                    <th>Saldo Awal</th>
                                    <th>Total Simpanan</th>
                                    <th>Keterangan</th>
                                    <th><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->id_simpanan }}</td>
                                        <td>{{ $data->anggota->nama_lengkap ?? '-' }}</td>
                                        <td>{{ ucfirst($data->jenis_simpanan) }}</td>
                                        <td>Rp {{ number_format($data->saldo_awal, 2, ',', '.') }}</td>
                                        <td>Rp {{ number_format($data->total_saldo, 2, ',', '.') }}</td>
                                        <td>{{ $data->keterangan ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('simpanan.edit', $data->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('simpanan.destroy', $data->id) }}"
                                                class="form-delete d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $data->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
