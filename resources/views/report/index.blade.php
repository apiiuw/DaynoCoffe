@extends('adminlte.layouts.app')

@section('title', 'Uangku | Halaman Laporan')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Keuangan</h1>
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
                                <form method="GET" action="{{ route('index.report') }}" class="form-inline mb-3">
                                    <div class="form-group mr-2">
                                        <label for="month" class="mr-2">Bulan</label>
                                        <select name="month" id="month" class="form-control">
                                            <option value="">Belum disetel</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="year" class="mr-2">Tahun</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">Belum disetel</option>
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Setel Periode</button>
                                    <a href="{{ route('index.report') }}" class="btn btn-secondary ml-2">Reset Periode</a>
                                </form>

                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Pemasukkan (Rp)</th>
                                            <th>Pengeluaran (Rp)</th>
                                            <th>Hutang (Rp)</th>
                                            <th>Tagihan (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData as $month => $data)
                                            <tr>
                                                <td>{{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                                <td>{{ number_format($data['income'], 0, ',', '.') }}</td>
                                                <td>{{ number_format($data['expense'], 0, ',', '.') }}</td>
                                                <td>{{ number_format($data['debt'], 0, ',', '.') }}</td>
                                                <td>{{ number_format($data['bill'], 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-danger">
                                                    Data laporan kosong atau tidak tersedia.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ number_format($totalIncome, 0, ',', '.') }}</th>
                                            <th>{{ number_format($totalExpense, 0, ',', '.') }}</th>
                                            <th>{{ number_format($totalDebt, 0, ',', '.') }}</th>
                                            <th>{{ number_format($totalBill, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                    <hr>
                                </table>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <strong>Keuntungan:</strong> Rp {{ number_format($profit, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-danger">
                                            <strong>Kerugian:</strong> Rp {{ number_format($loss, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <a href="{{ route('report.pdf', ['month' => request('month'), 'year' => request('year')]) }}" 
                                    class="btn btn-primary">
                                    <i class="fas fa-print"></i> Cetak Laporan
                                </a>

                            </div>
                        </div>
                    
                    </div>
                </div>
                
            </div>
    </div>
    </section>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
@endpush

