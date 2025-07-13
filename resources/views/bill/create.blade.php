@extends('adminlte.layouts.app')

@section('title', 'Uangku | Tambah Tagihan')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Tagihan</h1>
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
                                <form method="POST" action="{{ route('store.bill') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="date">Tanggal:</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Jenis Tagihan:</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Listrik">Listrik</option>
                                            <option value="Air">Air</option>
                                            <option value="Wifi">Internet / Wifi</option>
                                            <option value="Sampah">Biaya Sampah / Kebersihan Lingkungan</option>
                                            <option value="Lain-Lain">Lain-Lain</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Jumlah:</label>
                                        <input type="text" class="form-control" id="amount" name="amount" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date">Tenggat Waktu:</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="status_lunas"
                                                value="1">
                                            <label class="form-check-label" for="status_lunas">Lunas</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="status_belum_lunas" value="0" checked>
                                            <label class="form-check-label" for="status_belum_lunas">Belum Lunas</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">(Opsional) Deskripsi:</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('index.bill') }}" class="btn btn-danger">Kembali</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
