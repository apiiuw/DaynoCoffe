@extends('adminlte.layouts.app')

@section('title', 'Uangku | Edit Pemasukan')
 
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Edit Pemasukan</h1>
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
            <form method="POST" action="{{ route('update.income', $income->first()->id_incomes) }}">
                @csrf
                @method('PUT')

                <!-- Tanggal -->
                <div class="card order-card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Tanggal</label>
                            <input type="date" class="form-control" name="date[]" value="{{ $income->first()->date }}" required readonly>
                        </div>
                    </div>
                </div>

                <!-- List Pesanan -->
                <div id="order-cards">
                    @foreach ($income as $entry)
                        <div class="card order-card">
                            <div class="card-body">
                                <!-- Menu -->
                                <div class="form-group">
                                    <label>Menu</label>
                                    <select class="form-control menu-select" name="menu_id[]" required>
                                        <option value="">-- Pilih Menu --</option>
                                        @foreach ($menus as $menu)
                                            <option value="{{ $menu->id }}"
                                                {{ $menu->menu == $entry->description ? 'selected' : '' }}
                                                data-price="{{ (int) $menu->price }}"
                                                data-category="{{ $menu->category }}"
                                                data-menu="{{ $menu->menu }}">
                                                {{ $menu->menu }} - Rp{{ number_format($menu->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                        <option value="tip"
                                            data-price="{{ (int) ($entry->description == 'Tip' ? $entry->price : 0) }}"
                                            data-category="Tip"
                                            data-menu="Tip"
                                            {{ $entry->description == 'Tip' ? 'selected' : '' }}>
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
                                    <label>Harga Satuan</label>
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
                <a href="{{ route('index.income') }}" class="btn btn-danger mb-5">Kembali</a>
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