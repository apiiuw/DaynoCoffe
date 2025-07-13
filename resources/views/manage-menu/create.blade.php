@extends('adminlte.layouts.app')

@section('title', 'Kelola Menu | Tambah Menu')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Menu</h1>
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
                                <form method="POST" action="{{ route('manage-menu.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="category">Kategori Menu</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Espresso Based">Espresso Based</option>
                                            <option value="Milk Based">Milk Based</option>
                                            <option value="Manual Brew">Manual Brew</option>
                                            <option value="Tea Based">Tea Based</option>
                                            <option value="Food">Food</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="menu">Nama Menu</label>
                                        <input type="text" class="form-control" name="menu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Harga Menu</label>
                                        <input type="text" class="form-control" id="price" name="price" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('manage-menu.index') }}" class="btn btn-danger">Kembali</a>
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
    const priceInput = document.getElementById('price');

    priceInput.addEventListener('input', function (e) {
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
