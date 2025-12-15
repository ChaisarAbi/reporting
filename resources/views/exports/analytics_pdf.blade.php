<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report - Breakdown Reporting System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
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
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 8px 12px;
            font-weight: bold;
            border-left: 4px solid #333;
            margin-bottom: 15px;
            font-size: 14px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        table th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            color: #333;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .stats-grid {
            width: 100%;
            margin-bottom: 25px;
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
        }
        .stat-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            flex: 1;
            margin: 0 1%;
            box-sizing: border-box;
            min-height: 100px;
        }
        .stat-card:first-child {
            margin-left: 0;
        }
        .stat-card:last-child {
            margin-right: 0;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }
        .stat-label {
            color: #6c757d;
            font-size: 12px;
            font-weight: bold;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }
        .filters span {
            margin-right: 20px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 9px;
        }
        .page-break {
            page-break-before: always;
        }
        .chart-container {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .chart-image {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
        }
        .chart-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
            background-color: #f9f9f9;
            border: 1px dashed #ddd;
            border-radius: 6px;
        }
        .part-usage-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .part-chart-container {
            flex: 1;
            min-width: 300px;
        }
        .part-table-container {
            flex: 1;
            min-width: 300px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analytics Report - Breakdown Reporting System</h1>
        <p>Generated on: {{ date('d/m/Y H:i') }}</p>
        @if($filters['year'] || $filters['date_from'] || $filters['date_to'])
        <div class="filters">
            <strong>Filters Applied:</strong>
            @if($filters['year'])
                <span>Year: {{ $filters['year'] }}</span>
            @endif
            @if($filters['date_from'])
                <span>From: {{ date('d/m/Y', strtotime($filters['date_from'])) }}</span>
            @endif
            @if($filters['date_to'])
                <span>To: {{ date('d/m/Y', strtotime($filters['date_to'])) }}</span>
            @endif
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Key Statistics</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Reports</div>
                <div class="stat-value">{{ $stats['total_reports'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Average Repair Time</div>
                <div class="stat-value">
                    @if($stats['avg_repair_time'])
                        {{ round($stats['avg_repair_time']) }} min
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Top Machine</div>
                <div class="stat-value">{{ $stats['top_machine'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Top Event Type</div>
                <div class="stat-value">{{ $stats['top_event_type'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Top Part</div>
                <div class="stat-value">{{ $stats['top_part'] }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Monthly Breakdown Statistics</div>
        
        @if(isset($chartImages['monthly']))
        <div class="chart-container">
            <div class="chart-title">Monthly Breakdown Chart</div>
            <img src="{{ $chartImages['monthly'] }}" class="chart-image" alt="Monthly Breakdown Chart">
        </div>
        @elseif($monthlyStats->count() > 0)
        <div class="no-data">
            Chart generation failed. Displaying data table only.
        </div>
        @endif
        
        @if($monthlyStats->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Total Reports</th>
                    <th>Average Repair Time (min)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyStats as $stat)
                <tr>
                    <td>{{ $stat->year }}</td>
                    <td>{{ date('F', mktime(0, 0, 0, $stat->month, 1)) }}</td>
                    <td>{{ $stat->total_reports }}</td>
                    <td>{{ $stat->avg_repair_time ? round($stat->avg_repair_time) : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">No monthly breakdown data available</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Machine Breakdown Frequency</div>
        
        @if(isset($chartImages['machine']))
        <div class="chart-container">
            <div class="chart-title">Machine Breakdown Frequency Chart</div>
            <img src="{{ $chartImages['machine'] }}" class="chart-image" alt="Machine Breakdown Chart">
        </div>
        @elseif($machineBreakdowns->count() > 0)
        <div class="no-data">
            Chart generation failed. Displaying data table only.
        </div>
        @endif
        
        @if($machineBreakdowns->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Machine</th>
                    <th>Breakdown Count</th>
                    <th>Total Downtime</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalBreakdowns = $machineBreakdowns->sum('breakdown_count');
                @endphp
                @foreach($machineBreakdowns as $breakdown)
                @php
                    $percentage = $totalBreakdowns > 0 ? round(($breakdown->breakdown_count / $totalBreakdowns) * 100, 1) : 0;
                    $downtime = $breakdown->total_downtime_minutes ?? 0;
                @endphp
                <tr>
                    <td>{{ $breakdown->machine->name }}</td>
                    <td>{{ $breakdown->breakdown_count }}</td>
                    @php
                        $hours = $downtime > 0 ? round($downtime / 60, 1) : 0;
                    @endphp
                    <td>{{ $hours > 0 ? $hours . ' jam' : 'N/A' }}</td>
                    <td>{{ $percentage }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">No machine breakdown data available</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Event Type Frequency</div>
        
        @if(isset($chartImages['event']))
        <div class="chart-container">
            <div class="chart-title">Event Type Frequency Chart</div>
            <img src="{{ $chartImages['event'] }}" class="chart-image" alt="Event Type Chart">
        </div>
        @elseif($eventTypeFrequency->count() > 0)
        <div class="no-data">
            Chart generation failed. Displaying data table only.
        </div>
        @endif
        
        @if($eventTypeFrequency->count() > 0)
        @php
            $totalFrequency = $eventTypeFrequency->sum('frequency');
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Event Type</th>
                    <th>Frequency</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventTypeFrequency as $event)
                @php
                    $percentage = $totalFrequency > 0 ? round(($event->frequency / $totalFrequency) * 100, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->frequency }}</td>
                    <td>{{ $percentage }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">No event type frequency data available</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Part Usage Analysis</div>
        
        @if($partUsage->count() > 0)
        <div class="part-usage-grid">
            @if(isset($chartImages['part']))
            <div class="part-chart-container chart-container">
                <div class="chart-title">Part Usage Distribution</div>
                <img src="{{ $chartImages['part'] }}" class="chart-image" alt="Part Usage Chart">
            </div>
            @endif
            
            <div class="part-table-container">
                @php
                    $totalQuantity = $partUsage->sum('total_quantity');
                    $totalUsageCount = $partUsage->sum('usage_count');
                @endphp
                <table>
                    <thead>
                        <tr>
                            <th>Part Name</th>
                            <th>Total Quantity</th>
                            <th>Usage Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partUsage as $part)
                        @php
                            $percentage = $totalQuantity > 0 ? round(($part->total_quantity / $totalQuantity) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td>{{ $part->name }}</td>
                            <td>{{ $part->total_quantity }}</td>
                            <td>{{ $part->usage_count }}</td>
                            <td>{{ $percentage }}%</td>
                        </tr>
                        @endforeach
                        @if($totalQuantity > 0)
                        <tr style="font-weight: bold; background-color: #f8f9fa;">
                            <td>Total</td>
                            <td>{{ $totalQuantity }}</td>
                            <td>{{ $totalUsageCount }}</td>
                            <td>100%</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="no-data">No part usage data available</div>
        @endif
    </div>

    <div class="footer">
        <p>This report was automatically generated by the Breakdown Reporting System.</p>
        <p>Generated on: {{ date('d/m/Y H:i') }} | Page 1 of 1</p>
    </div>
</body>
</html>
