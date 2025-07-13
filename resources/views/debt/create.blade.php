@extends('adminlte.layouts.app')

@section('title', 'Uangku | Form Hutang')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Hutang</h1>
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
                                <form method="POST" action="{{ route('store.debt') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="category">Jenis Hutang</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Pilih Kategori --</option>
                                                <option value="Hutang Supplier">Hutang Supplier Bahan</option>
                                                <option value="Hutang Peralatan">Hutang Peralatan</option>
                                                <option value="Hutang Gaji">Hutang Gaji Karyawan</option>
                                                <option value="Hutang Bank">Hutang ke Bank</option>
                                                <option value="Hutang Lain-Lain">Lain-Lain</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>                            
                                    <div class="form-group">
                                        <label for="amount">Jumlah</label>
                                        <input type="number" class="form-control" id="amount" name="amount" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Tenggat Waktu</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>       
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('index.debt') }}" class="btn btn-danger">Kembali</a>
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
