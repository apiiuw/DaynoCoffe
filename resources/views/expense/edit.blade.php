@extends('adminlte.layouts.app')

@section('title', 'Uangku | Edit Pengeluaran')
 
{{-- @section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Pengeluaran</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('update.expense', $expense->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="date">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="{{ $expense->date }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Kategori</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Bahan">Bahan & Persediaan</option>
                                            <option value="Gaji Karyawan">Gaji Karyawan</option>
                                            <option value="Biaya Promosi">Biaya Promosi</option>
                                            <option value="Pemeliharaan & Peralatan">Pemeliharaan & Peralatan</option>
                                            <option value="Lain-Lain">Lain-Lain</option>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Jumlah</label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            value="{{ $expense->amount }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ $expense->description }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('index.expense') }}" class="btn btn-danger">Kembali</a>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection --}}

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Edit Pengeluaran</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="content">
        <div class="container-fluid">
            <form method="POST" action="{{ route('update.expense', $expenses->first()->id_expenses) }}">
                @csrf
                @method('PUT')

                <!-- Tanggal -->
                <div class="card order-card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Tanggal</label>
                            <input type="date" class="form-control" name="date[]" value="{{ $expenses->first()->date }}" required readonly>
                        </div>
                    </div>
                </div>

                <!-- List Pesanan -->
                <div id="order-cards">
                    @foreach ($expenses as $entry)
                        <div class="card order-card">
                            <div class="card-body">
                                <!-- Menu -->
                                <div class="form-group">
                                    <label>Menu</label>
                                    <select class="form-control menu-select" name="expenses_id[]" required>
                                        <option value="">-- Pilih Menu --</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->item == $entry->description ? 'selected' : '' }}
                                                data-price="{{ (int) $item->price }}"
                                                data-category="{{ $item->category }}"
                                                data-menu="{{ $item->item }}">
                                                {{ $item->item }} - Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                        <option value="Tip Karyawan"
                                            data-price="{{ (int) ($entry->description == 'Tip Karyawan' ? $entry->price : 0) }}"
                                            data-category="Tip Karyawan"
                                            data-menu="Tip Karyawan"
                                            {{ $entry->description == 'Tip Karyawan' ? 'selected' : '' }}>
                                            Tip
                                        </option>
                                    </select>
                                </div>

                                <!-- Jumlah -->
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control quantity" name="quantity[]" min="1" value="{{ $entry->quantity }}" required>
                                </div>

                                <!-- Harga -->
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" class="form-control price" name="price[]" value="{{ number_format($entry->price, 0, '', '') }}" required>
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea class="form-control description" name="description[]" rows="2" readonly>{{ $entry->description }}</textarea>
                                </div>

                                <!-- Total per item (hidden) -->
                                <input type="hidden" class="total-amount">
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total Harga -->
                <div class="card order-card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="number" class="form-control" id="total-price" name="amount" readonly>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mb-5">Simpan</button>
                <a href="{{ route('index.expense') }}" class="btn btn-danger mb-5">Kembali</a>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderCards = document.querySelectorAll('.order-card');

        const totalPriceField = document.getElementById('total-price');

        function updateTotalPrice() {
            let total = 0;
            orderCards.forEach(card => {
                const qtyField = card.querySelector('.quantity');
                const priceField = card.querySelector('.price');
                if (!qtyField || !priceField) return;

                const qty = parseFloat(qtyField.value) || 0;
                const price = parseFloat(priceField.value) || 0;
                total += qty * price;
            });
            totalPriceField.value = total;
        }

        function handleMenuChange(card, menuSelect) {
            const selectedOption = menuSelect.options[menuSelect.selectedIndex];
            const category = selectedOption.getAttribute('data-category');
            const price = selectedOption.getAttribute('data-price');
            const menuName = selectedOption.getAttribute('data-menu');

            const qtyField = card.querySelector('.quantity');
            const priceField = card.querySelector('.price');
            const descField = card.querySelector('.description');

            if (!qtyField || !priceField || !descField) return;

            if (category === 'Tip') {
                qtyField.value = 1;
                qtyField.readOnly = true;

                priceField.readOnly = false;
                priceField.value = price || 0;

                descField.value = 'Tip';
            } else {
                qtyField.readOnly = false;
                if (!qtyField.value || qtyField.value == 0) qtyField.value = 1;

                priceField.readOnly = true;
                priceField.value = price || 0;

                descField.value = menuName || '';
            }

            updateTotalPrice();
        }

        orderCards.forEach(card => {
            const menuSelect = card.querySelector('.menu-select');
            const qtyField = card.querySelector('.quantity');
            const priceField = card.querySelector('.price');

            if (!menuSelect || !qtyField || !priceField) return;

            // Trigger saat halaman load
            handleMenuChange(card, menuSelect);

            // Update saat menu diganti
            menuSelect.addEventListener('change', () => handleMenuChange(card, menuSelect));

            // Update total jika qty atau harga diubah manual
            qtyField.addEventListener('input', updateTotalPrice);
            priceField.addEventListener('input', updateTotalPrice);
        });

        updateTotalPrice();
    });
</script>
@endpush