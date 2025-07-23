@extends('adminlte.layouts.app')

@section('title', 'Dayno Kopi | Kelola Kategori Pengeluaran')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Kelola Kategori Pengeluaran</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header text-right">
                    <a href="{{ route('manage-expanses.create') }}" class="btn btn-success">Tambah Kategori</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Item</th>
                                <th>Harga Satuan</th>
                                <th>Keterangan</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expanses as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->item }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    {{-- <td>
                                        <a href="{{ route('manage-expanses.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">Hapus</button>

                                        <form id="delete-form-{{ $item->id }}" action="{{ route('manage-expanses.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data!',
            text: 'Anda yakin ingin menghapus kategori pengeluaran ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if (session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('success-destroy'))
        Swal.fire({
            title: 'Data Terhapus!',
            text: '{{ session('success-destroy') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endpush
