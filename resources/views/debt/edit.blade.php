@extends('adminlte.layouts.app')

@section('title', 'Uangku | Edit Hutang')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Hutang</h1>
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
                                <form method="POST" action="{{ route('update.debt', $debt->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="category">Jenis Hutang</label>
                                        <option value="">-- Pilih Kategori --</option>
                                            <option value="supplier">Hutang Supplier Bahan</option>
                                            <option value="Peralatan">Hutang Peralatan</option>
                                            <option value="gajiTertunda">Hutang Gaji Karyawan</option>
                                            <option value="Bank">Hutang Ke bang</option>
                                            <option value="HutangLain">Lain-Lain</option>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="{{ $debt->date }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Jumlah</label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            value="{{ $debt->amount }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date">Tanggal</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date"
                                            value="{{ $debt->due_date }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ $debt->description }}</textarea>
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
