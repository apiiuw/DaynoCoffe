@extends('adminlte.layouts.app')

@section('title', 'Kelola Kategori | Tambah Pengeluaran')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Kategori Pengeluaran</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('manage-expanses.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="category">Kategori</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Bahan Baku">Bahan Baku</option>
                                            <option value="Biaya Promosi">Biaya Promosi</option>
                                            <option value="Gaji Karyawan">Gaji Karyawan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="item">Nama Item</label>
                                        <input type="text" class="form-control" name="item" id="item" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nominal">Nominal</label>
                                        <input type="text" class="form-control" name="nominal" id="nominal" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('manage-expanses.index') }}" class="btn btn-danger">Kembali</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@push('scripts')
<script>
    const nominalInput = document.getElementById('nominal');

    nominalInput.addEventListener('input', function (e) {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa  = split[0].length % 3,
            rupiah  = split[0].substr(0, sisa),
            ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }
</script>
@endpush

@endsection
