<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kerusakan Mesin #{{ $breakdownReport->id }}</title>
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
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f2f2f2;
            padding: 8px;
            font-weight: bold;
            border-left: 4px solid #333;
            margin-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-new {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-in_progress {
            background-color: #ffeaa7;
            color: #e17055;
        }
        .status-done {
            background-color: #d4edda;
            color: #155724;
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
        <p>Laporan Kerusakan Mesin Detail</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informasi Laporan</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">ID Laporan</div>
                <div class="info-value">#{{ $breakdownReport->id }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal Lapor</div>
                <div class="info-value">{{ $breakdownReport->reported_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @if($breakdownReport->status == 'new')
                        <span class="status-badge status-new">Baru</span>
                    @elseif($breakdownReport->status == 'in_progress')
                        <span class="status-badge status-in_progress">Sedang Diperbaiki</span>
                    @elseif($breakdownReport->status == 'done')
                        <span class="status-badge status-done">Selesai</span>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Mesin</div>
                <div class="info-value">{{ $breakdownReport->machine->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Pelapor</div>
                <div class="info-value">{{ $breakdownReport->reporter->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Departemen</div>
                <div class="info-value">{{ $breakdownReport->department }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Line</div>
                <div class="info-value">{{ $breakdownReport->line }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Shift</div>
                <div class="info-value">{{ $breakdownReport->shift }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Deskripsi Masalah</div>
        <div style="padding: 10px; background-color: #f9f9f9; border-radius: 4px;">
            {{ $breakdownReport->problem_area }}
        </div>
    </div>

    @if($breakdownReport->description)
    <div class="section">
        <div class="section-title">Deskripsi Tambahan</div>
        <div style="padding: 10px; background-color: #f9f9f9; border-radius: 4px;">
            {{ $breakdownReport->description }}
        </div>
    </div>
    @endif

    @if($breakdownReport->eventTypes->count() > 0)
    <div class="section">
        <div class="section-title">Jenis Event</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($breakdownReport->eventTypes as $eventType)
                <tr>
                    <td>{{ $eventType->name }}</td>
                    <td>{{ $eventType->category }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($breakdownReport->causeTypes->count() > 0)
    <div class="section">
        <div class="section-title">Jenis Penyebab</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Penyebab</th>
                </tr>
            </thead>
            <tbody>
                @foreach($breakdownReport->causeTypes as $causeType)
                <tr>
                    <td>{{ $causeType->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($breakdownReport->breakdownParts->count() > 0)
    <div class="section">
        <div class="section-title">Part yang Diganti</div>
        <table>
            <thead>
                <tr>
                    <th>Part</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($breakdownReport->breakdownParts as $part)
                <tr>
                    <td>{{ $part->partType->name }}</td>
                    <td>{{ $part->quantity }}</td>
                    <td>{{ $part->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($breakdownReport->breakdownResponsibility)
    <div class="section">
        <div class="section-title">Tanggung Jawab</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Tanggung Jawab</div>
                <div class="info-value">
                    @php
                        $responsibilityLabels = [
                            'design_workshop' => 'Design Workshop',
                            'supplier_part' => 'Supplier Part',
                            'production_assy' => 'Production Assy',
                            'operator_mtc' => 'Operator MTC',
                            'other' => 'Lainnya'
                        ];
                    @endphp
                    {{ $responsibilityLabels[$breakdownReport->breakdownResponsibility->responsibility] ?? $breakdownReport->breakdownResponsibility->responsibility }}
                </div>
            </div>
            @if($breakdownReport->breakdownResponsibility->notes)
            <div class="info-item">
                <div class="info-label">Catatan Tanggung Jawab</div>
                <div class="info-value">{{ $breakdownReport->breakdownResponsibility->notes }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($breakdownReport->repair_start_at)
    <div class="section">
        <div class="section-title">Informasi Perbaikan</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Mulai Perbaikan</div>
                <div class="info-value">{{ $breakdownReport->repair_start_at->format('d/m/Y H:i') }}</div>
            </div>
            @if($breakdownReport->repair_end_at)
            <div class="info-item">
                <div class="info-label">Selesai Perbaikan</div>
                <div class="info-value">{{ $breakdownReport->repair_end_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Durasi Perbaikan</div>
                <div class="info-value">
                    @php
                        $duration = $breakdownReport->repair_start_at->diff($breakdownReport->repair_end_at);
                        $hours = $duration->h + ($duration->days * 24);
                        $minutes = $duration->i;
                    @endphp
                    {{ $hours }} jam {{ $minutes }} menit
                </div>
            </div>
            @endif
            @if($breakdownReport->maintenanceLeader)
            <div class="info-item">
                <div class="info-label">Leader Teknisi</div>
                <div class="info-value">{{ $breakdownReport->maintenanceLeader->name }}</div>
            </div>
            @endif
            @if($breakdownReport->machine_operational)
            <div class="info-item">
                <div class="info-label">Status Mesin</div>
                <div class="info-value">{{ $breakdownReport->machine_operational == 'yes' ? 'Operasional' : 'Tidak Operasional' }}</div>
            </div>
            @endif
            @if($breakdownReport->position)
            <div class="info-item">
                <div class="info-label">Posisi Kerusakan</div>
                <div class="info-value">{{ $breakdownReport->position }}</div>
            </div>
            @endif
            @if($breakdownReport->repair_action)
            <div class="info-item">
                <div class="info-label">Tindakan Perbaikan</div>
                <div class="info-value">
                    @php
                        $repairActionLabels = [
                            'penggantian_part' => 'Penggantian Part',
                            'hanya_adjust' => 'Hanya Adjust',
                            'overhaul' => 'Overhaul',
                            'kaizen_mekanik' => 'Kaizen Mekanik',
                            'lain_lain' => 'Lain-lain'
                        ];
                    @endphp
                    {{ $repairActionLabels[$breakdownReport->repair_action] ?? $breakdownReport->repair_action }}
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($breakdownReport->technician_notes)
    <div class="section">
        <div class="section-title">Catatan Teknisi</div>
        <div style="padding: 10px; background-color: #f9f9f9; border-radius: 4px;">
            {{ $breakdownReport->technician_notes }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem Breakdown Reporting System</p>
        <p>PT HI-LEX INDONESIA &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
