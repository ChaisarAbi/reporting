<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kerusakan Mesin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .status-new {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-in_progress {
            background-color: #ffeaa7;
            color: #e17055;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-done {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT HI-LEX INDONESIA</h1>
        <p>Laporan Kerusakan Mesin</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info">
        <p><strong>Total Laporan:</strong> {{ $reports->count() }}</p>
        <p><strong>Periode:</strong> {{ request('date_from') ? date('d/m/Y', strtotime(request('date_from'))) : 'Semua' }} 
           s/d {{ request('date_to') ? date('d/m/Y', strtotime(request('date_to'))) : 'Sekarang' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Mesin</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Posisi Kerusakan</th>
                <th>Mulai Perbaikan</th>
                <th>Selesai Perbaikan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>#{{ $report->id }}</td>
                <td>{{ $report->reported_at->format('d/m/Y H:i') }}</td>
                <td>{{ $report->machine->name }}</td>
                <td>{{ $report->reporter->name }}</td>
                <td>
                    @if($report->status == 'new')
                        <span class="status-new">Baru</span>
                    @elseif($report->status == 'in_progress')
                        <span class="status-in_progress">Sedang Diperbaiki</span>
                    @elseif($report->status == 'done')
                        <span class="status-done">Selesai</span>
                    @endif
                </td>
                <td>{{ $report->description ? substr($report->description, 0, 50) . '...' : '-' }}</td>
                <td>{{ $report->position ? substr($report->position, 0, 30) . (strlen($report->position) > 30 ? '...' : '') : '-' }}</td>
                <td>{{ $report->repair_start_at ? $report->repair_start_at->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $report->repair_end_at ? $report->repair_end_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem Breakdown Reporting System</p>
        <p>PT HI-LEX INDONESIA &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
