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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('store.income') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="date">Tanggal</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>

                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                        <option value="Tip">Tip</option>
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
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
                                </div>

                                <div class="form-group">
                                    <label for="amount">Total Harga</label>
                                    <input type="number" class="form-control" id="amount" name="amount" readonly required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="2" readonly></textarea>
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

    const categorySelect = document.getElementById('category');
    const menuGroup = document.getElementById('menu-group');
    const menuSelect = document.getElementById('menu-select');
    const quantityInput = document.getElementById('quantity');
    const amountInput = document.getElementById('amount');
    const descriptionTextarea = document.getElementById('description');

    let currentCategory = null;

    categorySelect.addEventListener('change', function () {
        const selectedCategory = this.value;
        currentCategory = selectedCategory;

        // Reset semua nilai
        menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';
        menuGroup.classList.add('d-none');
        amountInput.value = '';
        descriptionTextarea.value = '';
        quantityInput.value = 1;

        if (selectedCategory === 'Tip') {
            // Kategori Tip
            descriptionTextarea.removeAttribute('readonly');

            // Total harga = jumlah input
            quantityInput.addEventListener('input', handleTipInput);
        } else if (selectedCategory) {
            // Hapus handler tip
            quantityInput.removeEventListener('input', handleTipInput);
            descriptionTextarea.setAttribute('readonly', true);

            const filteredMenus = menus.filter(m => m.category === selectedCategory);
            filteredMenus.forEach(m => {
                const option = document.createElement('option');
                option.value = m.id;
                option.text = m.menu;
                option.dataset.price = m.price;
                menuSelect.appendChild(option);
            });

            menuGroup.classList.remove('d-none');
            updateAmount();
        } else {
            quantityInput.removeEventListener('input', handleTipInput);
            descriptionTextarea.setAttribute('readonly', true);
        }
    });

    menuSelect.addEventListener('change', updateAmount);
    quantityInput.addEventListener('input', function () {
        if (currentCategory !== 'Tip') {
            updateAmount();
        }
    });

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
</script>
@endpush

