@extends('adminlte.layouts.app')

@section('title', 'Kelola Menu | Edit Menu')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Menu</h1>
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
                                <form method="POST" action="{{ route('manage-menu.update', $menu->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="category">Kategori Menu</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Espresso Based" {{ $menu->category == 'Espresso Based' ? 'selected' : '' }}>Espresso Based</option>
                                            <option value="Milk Based" {{ $menu->category == 'Milk Based' ? 'selected' : '' }}>Milk Based</option>
                                            <option value="Manual Brew" {{ $menu->category == 'Manual Brew' ? 'selected' : '' }}>Manual Brew</option>
                                            <option value="Tea Based" {{ $menu->category == 'Tea Based' ? 'selected' : '' }}>Tea Based</option>
                                            <option value="Food" {{ $menu->category == 'Food' ? 'selected' : '' }}>Food</option>
                                            <option value="Other" {{ !in_array($menu->category, ['Espresso Based', 'Milk Based', 'Manual Brew', 'Tea Based', 'Food']) ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <!-- Inputan untuk kategori Other -->
                                    <div class="form-group" id="other-category" style="{{ !in_array($menu->category, ['Espresso Based', 'Milk Based', 'Manual Brew', 'Tea Based', 'Food']) ? 'display: block;' : 'display: none;' }}">
                                        <label for="other-category-input">Kategori Lainnya</label>
                                        <input type="text" class="form-control" id="other-category-input" name="other_category" value="{{ !in_array($menu->category, ['Espresso Based', 'Milk Based', 'Manual Brew', 'Tea Based', 'Food']) ? $menu->category : '' }}" placeholder="Ketikkan kategori lain...">
                                    </div>

                                    <div class="form-group">
                                        <label for="menu">Nama Menu</label>
                                        <input type="text" class="form-control" name="menu" value="{{ $menu->menu }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Harga Satuan</label>
                                        <input type="text" class="form-control" id="price" name="price" value="Rp {{ number_format($menu->price, 0, ',', '.') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="availability">Ketersediaan</label>
                                        <select class="form-control" id="availability" name="availability" required>
                                            <option value="Belum Diatur" {{ $menu->availability == 'Belum Diatur' ? 'selected' : '' }}>Belum Diatur</option>
                                            <option value="Tersedia" {{ $menu->availability == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="Tidak Tersedia" {{ $menu->availability == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        </select>
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

    // Menambahkan event listener untuk dropdown kategori
    document.getElementById('category').addEventListener('change', function () {
        const otherCategoryInput = document.getElementById('other-category');
        const category = this.value;

        if (category === 'Other') {
            otherCategoryInput.style.display = 'block';  // Menampilkan input untuk kategori lain
        } else {
            otherCategoryInput.style.display = 'none';  // Menyembunyikan input untuk kategori lain
        }
    });

    // Menyimpan kategori lain jika dipilih
    document.querySelector('form').addEventListener('submit', function (e) {
        const categorySelect = document.getElementById('category');
        const otherCategoryInput = document.getElementById('other-category-input');

        if (categorySelect.value === 'Other' && otherCategoryInput.value) {
            categorySelect.value = otherCategoryInput.value;  // Menyimpan input kategori lain ke kolom kategori
        }
    });
</script>
@endpush

@endsection
