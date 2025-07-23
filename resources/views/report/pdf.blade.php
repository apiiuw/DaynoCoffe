<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Bulanan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Keuangan</h1>
    <p>Tanggal & Waktu Cetak : {{ $currentDateTime }}</p>
    @php
        use Carbon\Carbon;

        $periode = '-';

        if (!empty($selectedYear) && !empty($selectedMonth)) {
            try {
                $periode = Carbon::createFromFormat('Y-m', $selectedYear . '-' . str_pad($selectedMonth, 2, '0', STR_PAD_LEFT))
                            ->locale('id')->translatedFormat('F Y');
            } catch (\Exception $e) {
                $periode = '-';
            }
        }
    @endphp
    <p>Periode: {{ $periode }}</p>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Pemasukan (Rp)</th>
                <th>Pengeluaran (Rp)</th>
                <th>Hutang (Rp)</th>
                <th>Tagihan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $month => $data)
                <tr>
                    <td>{{ Carbon::createFromFormat('Y-m', $month)->locale('id')->translatedFormat('F Y') }}</td>
                    <td>{{ number_format($data['income'], 0, ',', '.') }}</td>
                    <td>{{ number_format($data['expense'], 0, ',', '.') }}</td>
                    <td>{{ number_format($data['debt'], 0, ',', '.') }}</td>
                    <td>{{ number_format($data['bill'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
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
    </table>
    <br><br>
    <table>
        <tr>
            <th>Keuntungan (Rp)</th>
            <td>{{ number_format($profit, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Kerugian (Rp)</th>
            <td>{{ number_format($loss, 0, ',', '.') }}</td>
        </tr>
    </table>

</body>
</html>
