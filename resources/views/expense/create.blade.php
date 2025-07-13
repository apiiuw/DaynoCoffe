@extends('adminlte.layouts.app')

@section('title', 'Uangku | Form Pengeluaran')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Pengeluaran</h1>
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
                                <form method="POST" action="{{ route('store.expense') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="date">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
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
                                        <input type="number" class="form-control" id="amount" name="amount" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
@endsection
