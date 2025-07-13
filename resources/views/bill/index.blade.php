@extends('adminlte.layouts.app')

@section('title', 'Uangku | Halaman Tagihan')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Tagihan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ '/' }}">Home</a></li>
                            <li class="breadcrumb-item active">Tagihan</li>
                        </ol>
                    </div><!-- /.col -->
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
                                    <a href="{{ route('create.bill') }}" class="btn btn-success">Tambah Tagihan</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>
                                                <a
                                                    href="{{ request()->fullUrlWithQuery(['field' => 'date', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
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
                                            <th>Kategori</th>
                                            <th>
                                                <a
                                                    href="{{ request()->fullUrlWithQuery(['field' => 'amount', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
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
                                                <a
                                                    href="{{ request()->fullUrlWithQuery(['field' => 'due_date', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
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
                                            <th>Status</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bills as $bill)
                                            <tr>
                                                <td>{{ $loop->iteration + ($bills->currentPage() - 1) * $bills->perPage() }}
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($bill->date)) }}</td>
                                                <td>{{ $bill->category }}</td>
                                                <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                                <td>{{ date('d-m-Y', strtotime($bill->due_date)) }}</td>
                                                <td>{{ $bill->status }}</td>
                                                <td>{{ $bill->description }}</td>
                                                <td>
                                                    <a href="{{ route('edit.bill', $bill->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    @if ($bill->status == 'Belum Lunas')
                                                        <form action="{{ route('bills.markAsPaid', $bill->id) }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('PUT')
                                                        </form>
                                                    @else
                                                        <!-- Hapus notifikasi terkait jika status lunas -->
                                                        <script>
                                                            $(document).ready(function() {
                                                                $('#notificationList').find(`a[href="/bill/{{ $bill->id }}"]`).remove();
                                                            });
                                                        </script>
                                                    @endif
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $bill->id }})">Delete</button>
                                                    <form id="delete-form-{{ $bill->id }}"
                                                        action="{{ route('delete.bill', $bill->id) }}" method="POST"
                                                        style="display: none;">
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
                                <p>Total Tagihan: Rp {{ number_format($totalBill, 0, ',', '.') }}</p>
                                <div class="d-flex justify-content-center">
                                    {{ $bills->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chart Section -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Diagram Tagihan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="billViewSelector">Tampilan Data:</label>
                            <select id="billViewSelector" class="form-control" style="max-width: 200px;">
                                <option value="monthly" selected>Per Bulan</option>
                                <option value="daily">Per Hari</option>
                            </select>
                        </div>
        <canvas id="billChart"></canvas>
    </div>
</div>
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

        function markAsPaid(id) {
            // Kirim permintaan Ajax untuk menandai tagihan sebagai lunas
            $.ajax({
                url: `/bills/${id}/mark-as-paid`, // Sesuaikan dengan rute yang telah Anda definisikan
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Tampilkan pesan sukses
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Refresh halaman setelah 2 detik
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error('Error marking bill as paid:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal menandai tagihan sebagai lunas',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
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
        var ctx = document.getElementById('billChart').getContext('2d');

        const monthlyData = {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Tagihan Bulanan',
                data: {!! json_encode($billData->values()) !!},
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true
            }]
        };

    const dailyData = {
        labels: {!! json_encode($dailyBillLabels) !!},
        datasets: [{
            label: 'Tagihan Harian',
            data: {!! json_encode($dailyBillValues) !!},
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            fill: true
        }]
    };

    const config = {
        type: 'line',
        data: monthlyData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const billChart = new Chart(ctx, config);

    document.getElementById('billViewSelector').addEventListener('change', function () {
        if (this.value === 'daily') {
            billChart.data = dailyData;
        } else {
            billChart.data = monthlyData;
        }
        billChart.update();
    });
});
</script>
@endpush
