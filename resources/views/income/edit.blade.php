@extends('adminlte.layouts.app')

@section('title', 'Uangku | Edit Pemasukan')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Edit Pemasukan</h1></div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('update.income', $income->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="date">Tanggal</label>
                                    <input type="date" class="form-control" name="date" value="{{ $income->date }}" readonly required>
                                </div>

                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}" {{ $income->category === $category ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                        <option value="Tip" {{ $income->category === 'Tip' ? 'selected' : '' }}>Tip</option>
                                    </select>
                                </div>

                                <div class="form-group d-none" id="menu-group">
                                    <label for="menu">Menu</label>
                                    <select class="form-control" id="menu-select">
                                        <option value="">-- Pilih Menu --</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Jumlah</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="">
                                </div>

                                <div class="form-group">
                                    <label for="amount">Total Harga</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="{{ $income->amount }}" readonly required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="2" {{ $income->category == 'Tip' ? '' : 'readonly' }}>{{ $income->description }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('index.income') }}" class="btn btn-danger">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const menus = @json($menus);
    const currentCategory = @json($income->category);
    const currentDescription = @json($income->description);
    const quantityInput = document.getElementById('quantity');
    const amountInput = document.getElementById('amount');
    const descriptionTextarea = document.getElementById('description');
    const categorySelect = document.getElementById('category');
    const menuGroup = document.getElementById('menu-group');
    const menuSelect = document.getElementById('menu-select');

    function handleTipInput() {
        amountInput.value = quantityInput.value || '';
    }

    function updateAmount() {
        const selected = menuSelect.options[menuSelect.selectedIndex];
        const price = selected ? selected.dataset.price : 0;
        const quantity = quantityInput.value || 1;

        if (price) {
            amountInput.value = price * quantity;
            descriptionTextarea.value = selected.text;
        } else {
            amountInput.value = '';
            descriptionTextarea.value = '';
        }
    }

    function loadMenusForCategory(category) {
        menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';
        const filteredMenus = menus.filter(m => m.category === category);

        let foundSelected = false;

        filteredMenus.forEach(m => {
            const option = document.createElement('option');
            option.value = m.id;
            option.text = m.menu;
            option.dataset.price = m.price;

            if (m.menu === currentDescription) {
                option.selected = true;
                foundSelected = true;

                const price = parseFloat(m.price);
                const amount = parseFloat(amountInput.value);
                if (price > 0) {
                    const qty = Math.round(amount / price);
                    quantityInput.value = qty;
                }

                descriptionTextarea.value = m.menu;
            }

            menuSelect.appendChild(option);
        });

        menuGroup.classList.remove('d-none');

        if (!foundSelected) {
            menuSelect.selectedIndex = 0;
            amountInput.value = '';
            descriptionTextarea.value = '';
        }
    }

    categorySelect.addEventListener('change', function () {
        const selectedCategory = this.value;
        if (selectedCategory === 'Tip') {
            menuGroup.classList.add('d-none');
            descriptionTextarea.removeAttribute('readonly');
            quantityInput.addEventListener('input', handleTipInput);
            handleTipInput();
        } else {
            menuGroup.classList.add('d-none');
            quantityInput.removeEventListener('input', handleTipInput);
            descriptionTextarea.setAttribute('readonly', true);
            if (selectedCategory) {
                loadMenusForCategory(selectedCategory);
                menuGroup.classList.remove('d-none');
            }
        }
    });

    menuSelect.addEventListener('change', updateAmount);
    quantityInput.addEventListener('input', function () {
        if (categorySelect.value !== 'Tip') {
            updateAmount();
        }
    });

    // Inisialisasi saat load halaman
    window.addEventListener('DOMContentLoaded', function () {
        categorySelect.value = currentCategory;

        if (currentCategory === 'Tip') {
            descriptionTextarea.removeAttribute('readonly');
            quantityInput.addEventListener('input', handleTipInput);
            handleTipInput();
        } else {
            descriptionTextarea.setAttribute('readonly', true);
            loadMenusForCategory(currentCategory);

            // Cari menu dengan nama sama seperti description
            const matchedMenu = menus.find(m => m.category === currentCategory && m.menu === currentDescription);
            if (matchedMenu) {
                const price = matchedMenu.price;
                const amount = parseFloat(amountInput.value);
                if (price > 0) {
                    const qty = Math.round(amount / price);
                    quantityInput.value = qty;
                }
            }
        }
    });

</script>
@endpush
