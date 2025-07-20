@extends('adminlte.layouts.app')

@section('title', 'Kelola Kategori | Edit Pengeluaran')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Kategori Pengeluaran</h1>
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
                                <form method="POST" action="{{ route('manage-expanses.update', $expanse->id) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group">
                                        <label for="category">Kategori</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Bahan Baku" {{ $expanse->category == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                                            <option value="Biaya Promosi" {{ $expanse->category == 'Biaya Promosi' ? 'selected' : '' }}>Biaya Promosi</option>
                                            <option value="Gaji Karyawan" {{ $expanse->category == 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                                            <option value="Other" {{ $expanse->category != 'Bahan Baku' && $expanse->category != 'Biaya Promosi' && $expanse->category != 'Gaji Karyawan' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <!-- Inputan untuk kategori Other -->
                                    <div class="form-group" id="other-category" style="{{ $expanse->category != 'Bahan Baku' && $expanse->category != 'Biaya Promosi' && $expanse->category != 'Gaji Karyawan' ? 'display: block;' : 'display: none;' }}">
                                        <label for="other-category-input">Kategori Lainnya</label>
                                        <input type="text" class="form-control" id="other-category-input" name="other_category" value="{{ $expanse->category != 'Bahan Baku' && $expanse->category != 'Biaya Promosi' && $expanse->category != 'Gaji Karyawan' ? $expanse->category : '' }}" placeholder="Ketikkan kategori lain...">
                                    </div>

                                    <div class="form-group">
                                        <label for="item">Nama Item</label>
                                        <input type="text" class="form-control" name="item" id="item" value="{{ $expanse->item }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Harga Satuan</label>
                                        <input type="text" class="form-control" name="price" id="price" value="Rp {{ number_format($expanse->price, 0, ',', '.') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3">{{ $expanse->keterangan }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
    const nominalInput = document.getElementById('price');

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
</script>
@endpush

@endsection
