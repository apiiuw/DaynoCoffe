@extends('adminlte.layouts.app')

@section('title', 'Uangku | Halaman Hutang')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Hutang</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-right">
                                    <a href="{{ route('create.debt') }}" class="btn btn-success">Tambah Hutang</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>                                            
                                            <th>Jenis Hutang</th> 
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'date', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
                                                    Tanggal
                                                    @if (request()->query('field') == 'date')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up"></i>
                                                        @elseif(request()->query('sort') == 'desc')
                                                            <i class="fas fa-sort-down"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>                                            
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'amount', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
                                                    Jumlah
                                                    @if (request()->query('field') == 'amount')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up"></i>
                                                        @elseif(request()->query('sort') == 'desc')
                                                            <i class="fas fa-sort-down"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>                                                                                    
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'due_date', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
                                                    Tenggat Waktu
                                                    @if (request()->query('field') == 'due_date')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up"></i>
                                                        @elseif(request()->query('sort') == 'desc')
                                                            <i class="fas fa-sort-down"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($debts as $debt)
                                            <tr>
                                                <td>{{ $loop->iteration + ($debts->currentPage() - 1) * $debts->perPage() }}</td>
                                                <td>{{ $debt->category }}</td>
                                                <td>{{ date('d-m-Y', strtotime($debt->date)) }}</td>                                                
                                                <td>Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
                                                <td>{{ date('d-m-Y', strtotime($debt->due_date)) }}</td>       
                                                <td>{{ $debt->description }}</td>
                                                <td>
                                                    <a href="{{ route('edit.debt', $debt->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $debt->id }})">Delete</button>
                                                    <form id="delete-form-{{ $debt->id }}" action="{{ route('delete.debt', $debt->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <p>Total Hutang: Rp {{ number_format($totalDebt, 0, ',', '.') }}</p>
                                <div class="d-flex justify-content-center">
                                    {{ $debts->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chart Section -->
                <div class="form-group mb-3">
                    <label for="viewSelector">Tampilan Data:</label>
                    <select id="viewSelector" class="form-control" style="max-width: 200px;">
                        <option value="monthly" selected>Per Bulan</option>
                        <option value="daily">Per Hari</option>
                    </select>
                </div>
                <canvas id="debtChart"></canvas>

    </div>
    </section>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Data!',
                text: 'Anda yakin ingin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('debtChart').getContext('2d');

        const monthlyLabels = {!! json_encode($months) !!};
        const monthlyData = {!! json_encode($debtData->values()) !!};

        const dailyLabels = {!! json_encode($dailyDebtLabels) !!};
        const dailyData = {!! json_encode($dailyDebtValues) !!};

        let currentType = 'monthly';

        let debtChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Hutang',
                    data: monthlyData,
                    borderColor: 'rgba(255, 111, 0, 1)',
                    backgroundColor: 'rgba(255, 111, 0, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.getElementById('viewSelector').addEventListener('change', function () {
            const selected = this.value;
            if (selected === 'daily' && currentType !== 'daily') {
                debtChart.data.labels = dailyLabels;
                debtChart.data.datasets[0].data = dailyData;
                currentType = 'daily';
            } else if (selected === 'monthly' && currentType !== 'monthly') {
                debtChart.data.labels = monthlyLabels;
                debtChart.data.datasets[0].data = monthlyData;
                currentType = 'monthly';
            }
            debtChart.update();
        });
    });
</script>



@endpush
