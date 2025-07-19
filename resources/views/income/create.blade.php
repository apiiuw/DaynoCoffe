@extends('adminlte.layouts.app')

@section('title', 'Uangku | Form Pemasukan')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Tambah Pemasukan</h1></div>
            </div>
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
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('store.income') }}">
                        @csrf
                        
                        <!-- Tanggal Form - Fixed at the top -->
                        <div class="card order-card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="date">Tanggal</label>
                                    <input type="date" class="form-control" name="date[]" value="{{ now()->setTimezone('Asia/Jakarta')->format('Y-m-d') }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <!-- Pesanan Form (Menu, Quantity, Price, Description) -->
                        <div id="order-cards">
                            <!-- Form pesanan baru akan ditambahkan di sini setelah menekan tombol 'Tambah Pesanan' -->
                        </div>

                        <!-- Tambah Pesanan Button -->
                        <div class="text-center">
                            <a href="javascript:void(0);" id="add-order" class="btn btn-success mb-3">Tambah Pesanan</a>
                        </div>

                        <!-- Total Harga (Only one total price field at the bottom) -->
                        <div class="card order-card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="total-price">Total Harga</label>
                                    <input type="number" class="form-control" id="total-price" name="amount[]" readonly required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-5">Simpan</button>
                        <a href="{{ route('index.income') }}" class="btn btn-danger mb-5">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const addOrderButton = document.getElementById('add-order');
    const orderCardsContainer = document.getElementById('order-cards');
    let orderCount = 1;

    // Fungsi untuk menambahkan form pesanan baru
    addOrderButton.addEventListener('click', () => {
        orderCount++;

        const newCard = document.createElement('div');
        newCard.classList.add('card', 'order-card');
        newCard.innerHTML = `
            <div class="card-body">
                <!-- Menu -->
                <div class="form-group">
                    <label for="menu-select">Menu</label>
                    <select class="form-control menu-select" name="menu_id[]" required>
                        <option value="">-- Pilih Menu --</option>
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}" data-category="{{ $menu->category }}">
                                {{ $menu->menu }} - Rp{{ number_format($menu->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                        <option value="tip" data-price="0" data-category="Tip">Tip</option>
                    </select>
                </div>

                <!-- Jumlah -->
                <div class="form-group quantity-group">
                    <label for="quantity">Jumlah</label>
                    <input type="number" class="form-control quantity" name="quantity[]" min="1" value="1" readonly required>
                </div>

                <!-- Harga -->
                <div class="form-group price-group">
                    <label for="price">Harga</label>
                    <input type="number" class="form-control price" name="price[]" required>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control description" name="description[]" rows="2" readonly></textarea>
                </div>
            </div>

            <!-- Total Harga (Hidden) -->
            <div class="card-body" style="display: none;">
                <div class="form-group">
                    <label for="total-amount">Total Harga</label>
                    <input type="number" class="form-control total-amount" readonly>
                </div>
            </div>
        `;
        orderCardsContainer.appendChild(newCard);

        // Panggil updateAmount untuk menu pertama
        updateAmount(newCard);

        // Event listener untuk perubahan menu atau jumlah
        newCard.querySelector('.menu-select').addEventListener('change', function() {
            updateAmount(newCard);
        });

        newCard.querySelector('.quantity').addEventListener('input', function() {
            updateAmount(newCard);
        });

        // Tambahkan event listener pada input harga untuk Tip
        newCard.querySelector('.price').addEventListener('input', function() {
            updateAmount(newCard); // Update total amount setelah harga dimasukkan
        });
    });

    // Fungsi untuk menghitung harga dan total
    function updateAmount(card) {
        const menuSelect = card.querySelector('.menu-select');
        const priceInput = card.querySelector('.price');
        const quantityInput = card.querySelector('.quantity');
        const totalAmountInput = card.querySelector('.total-amount');
        const descriptionTextarea = card.querySelector('.description');

        const selected = menuSelect.options[menuSelect.selectedIndex];
        const price = selected ? selected.dataset.price : 0;
        const quantity = quantityInput.value || 1;

        // Di dalam fungsi updateAmount() 
        if (selected.value === 'tip') {
            // Logika untuk Tip
            descriptionTextarea.value = "Tip"; // Set description as "Tip"
            priceInput.removeAttribute('readonly'); // Allow user to input price for tip
            quantityInput.value = 1;  // Set quantity as 1 for Tip
            quantityInput.setAttribute('readonly', true);  // Set quantity as readonly
            totalAmountInput.value = priceInput.value * quantity; // Set total amount as price * quantity (1 for Tip)
        } else {
            descriptionTextarea.value = selected.text.split(' - ')[0];
            priceInput.value = price;
            priceInput.value = parseFloat(price).toFixed(0);
            totalAmountInput.value = price * quantity;  // Calculate total amount for other menus
            quantityInput.removeAttribute('readonly'); // Show quantity input for other menus
        }

        // Pastikan jika description kosong, kirimkan string kosong
        descriptionTextarea.value = descriptionTextarea.value || '';  // If empty, make it an empty string

        // Update total price at the bottom of the page
        updateTotalPrice();
    }

    // Update total price based on all order cards
    function updateTotalPrice() {
        let total = 0;
        const amountInputs = document.querySelectorAll('.order-card .total-amount'); // Only consider the hidden total amount inputs
        amountInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const totalPriceInput = document.getElementById('total-price');  // Ensure the correct ID is used for the total price
        totalPriceInput.value = total;
    }
</script>
@endpush
