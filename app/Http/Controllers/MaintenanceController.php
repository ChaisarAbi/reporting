<?php

namespace App\Http\Controllers;

use App\Models\BreakdownReport;
use App\Models\EventType;
use App\Models\CauseType;
use App\Models\PartType;
use App\Models\BreakdownPart;
use App\Models\BreakdownResponsibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display dashboard for maintenance
     */
    public function dashboard(Request $request)
    {
        // Query untuk statistik (tanpa pagination, semua data)
        $statsQuery = BreakdownReport::query();
        
        // Apply filters untuk statistik
        if ($request->filled('status')) {
            $statsQuery->where('status', $request->status);
        }
        
        if ($request->filled('machine_id')) {
            $statsQuery->where('machine_id', $request->machine_id);
        }
        
        if ($request->filled('date_from')) {
            $statsQuery->whereDate('reported_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $statsQuery->whereDate('reported_at', '<=', $request->date_to);
        }
        
        $stats = [
            'total_reports' => $statsQuery->count(),
            'new_reports' => $statsQuery->clone()->where('status', 'new')->count(),
            'in_progress_reports' => $statsQuery->clone()->where('status', 'in_progress')->count(),
            'done_reports' => $statsQuery->clone()->where('status', 'done')->count(),
        ];

        // Query untuk recent reports
        $recentQuery = BreakdownReport::query();
        
        // Apply filters untuk recent reports
        if ($request->filled('status')) {
            $recentQuery->where('status', $request->status);
        }
        
        if ($request->filled('machine_id')) {
            $recentQuery->where('machine_id', $request->machine_id);
        }
        
        if ($request->filled('date_from')) {
            $recentQuery->whereDate('reported_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $recentQuery->whereDate('reported_at', '<=', $request->date_to);
        }

        $recentReports = $recentQuery->with(['machine', 'reporter'])
            ->orderBy('reported_at', 'desc')
            ->limit(10)
            ->get();
            
        // Get all machines for filter dropdown
        $machines = \App\Models\Machine::orderBy('name')->get();

        return view('maintenance.dashboard', compact('stats', 'recentReports', 'machines'));
    }

    /**
     * Show all reports with filtering
     */
    public function reports(Request $request)
    {
        $query = BreakdownReport::with(['machine', 'reporter']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('reported_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('reported_at', '<=', $request->date_to);
        }

        $reports = $query->orderBy('reported_at', 'desc')->paginate(15)->appends(request()->query());
        
        // Get all machines for filter dropdown
        $machines = \App\Models\Machine::orderBy('name')->get();

        return view('maintenance.reports', compact('reports', 'machines'));
    }

    /**
     * Display the specified breakdown report
     */
    public function show(BreakdownReport $breakdownReport)
    {
        $breakdownReport->load([
            'machine',
            'reporter',
            'eventTypes',
            'causeTypes',
            'breakdownParts.partType',
            'breakdownResponsibility',
            'maintenanceLeader'
        ]);

        return view('maintenance.show', compact('breakdownReport'));
    }

    /**
     * Start repair process
     */
    public function startRepair(BreakdownReport $breakdownReport)
    {
        $breakdownReport->update([
            'status' => 'in_progress',
            'repair_start_at' => now(),
            'maintenance_leader_id' => Auth::id(),
        ]);

        return redirect()->route('maintenance.show', $breakdownReport)
            ->with('success', 'Perbaikan telah dimulai.');
    }

    /**
     * Show form to complete repair
     */
    public function showCompleteForm(BreakdownReport $breakdownReport)
    {
        if ($breakdownReport->status !== 'in_progress') {
            return redirect()->route('maintenance.show', $breakdownReport)
                ->with('error', 'Laporan harus dalam status "Sedang Diperbaiki" untuk dapat diselesaikan.');
        }

        $eventTypes = EventType::all();
        $causeTypes = CauseType::all();
        $partTypes = PartType::all();

        return view('maintenance.complete', compact(
            'breakdownReport',
            'eventTypes',
            'causeTypes',
            'partTypes'
        ));
    }

    /**
     * Complete repair process
     */
    public function completeRepair(Request $request, BreakdownReport $breakdownReport)
    {
        if ($breakdownReport->status !== 'in_progress') {
            return redirect()->route('maintenance.show', $breakdownReport)
                ->with('error', 'Laporan harus dalam status "Sedang Diperbaiki" untuk dapat diselesaikan.');
        }

        $validated = $request->validate([
            'position' => 'required|string|max:200',
            'event_types' => 'required|array',
            'event_types.*' => 'exists:event_types,id',
            'repair_action' => 'required|in:penggantian_part,hanya_adjust,overhaul,kaizen_mekanik,lain_lain',
            'cause_types' => 'required|array',
            'cause_types.*' => 'exists:cause_types,id',
            'parts' => 'nullable|array',
            'parts.*.part_type_id' => 'nullable|exists:part_types,id',
            'parts.*.quantity' => 'nullable|integer|min:1',
            'parts.*.notes' => 'nullable|string',
            'responsibility' => 'required|in:design_workshop,supplier_part,production_assy,operator_mtc,other',
            'responsibility_notes' => 'nullable|string',
            'machine_operational' => 'required|in:yes,no',
            'technician_notes' => 'nullable|string',
        ], [
            'parts.*.part_type_id.exists' => 'Part yang dipilih tidak valid.',
            'parts.*.quantity.integer' => 'Jumlah part harus berupa angka.',
            'parts.*.quantity.min' => 'Jumlah part minimal 1.',
        ]);

        // Update breakdown report
        $breakdownReport->update([
            'status' => 'done',
            'repair_end_at' => now(),
            'position' => $validated['position'],
            'repair_action' => $validated['repair_action'],
            'machine_operational' => $validated['machine_operational'],
            'technician_notes' => $validated['technician_notes'],
        ]);

        // Sync event types
        $breakdownReport->eventTypes()->sync($validated['event_types']);

        // Sync cause types
        $breakdownReport->causeTypes()->sync($validated['cause_types']);

        // Handle parts
        if (isset($validated['parts'])) {
            // Delete existing parts
            $breakdownReport->breakdownParts()->delete();

            // Create new parts - only if part_type_id is provided
            foreach ($validated['parts'] as $partData) {
                if (!empty($partData['part_type_id']) && !empty($partData['quantity'])) {
                    BreakdownPart::create([
                        'breakdown_report_id' => $breakdownReport->id,
                        'part_type_id' => $partData['part_type_id'],
                        'quantity' => $partData['quantity'],
                        'notes' => $partData['notes'] ?? null,
                    ]);
                }
            }
        }

        // Handle responsibility
        BreakdownResponsibility::updateOrCreate(
            ['breakdown_report_id' => $breakdownReport->id],
            [
                'responsibility' => $validated['responsibility'],
                'notes' => $validated['responsibility_notes'] ?? null,
            ]
        );

        return redirect()->route('maintenance.show', $breakdownReport)
            ->with('success', 'Perbaikan telah selesai dan laporan telah ditutup.');
    }

    /**
     * Show analytics dashboard
     */
    public function analytics(Request $request)
    {
        // Create base query for filters
        $baseQuery = BreakdownReport::where('status', 'done');

        // Apply date range filters
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $year = $request->year;

        // Apply date range filter
        if ($dateFrom && $dateTo) {
            $baseQuery->whereBetween('reported_at', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $baseQuery->whereDate('reported_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $baseQuery->whereDate('reported_at', '<=', $dateTo);
        }

        // Apply year filter if no date range
        if (!$dateFrom && !$dateTo && $year) {
            $baseQuery->whereYear('reported_at', $year);
        }

        // Get monthly breakdown statistics (only for done reports)
        // Get last 12 months from the latest date in data
        $monthlyStats = (clone $baseQuery)->selectRaw('
                YEAR(reported_at) as year,
                MONTH(reported_at) as month,
                COUNT(*) as total_reports,
                AVG(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as avg_repair_time
            ')
            ->groupByRaw('YEAR(reported_at), MONTH(reported_at)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse(); // Reverse to show oldest to newest in chart (left to right: newest → oldest)

        // Get machine breakdown frequency with total downtime (all reports)
        $machineBreakdowns = (clone $baseQuery)->selectRaw('
                machine_id,
                COUNT(*) as breakdown_count,
                SUM(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as total_downtime_minutes
            ')
            ->with('machine')
            ->whereNotNull('repair_start_at')
            ->whereNotNull('repair_end_at')
            ->groupBy('machine_id')
            ->orderBy('breakdown_count', 'desc')
            ->limit(10)
            ->get();

        // Get event type frequency (only for done reports)
        // Use subquery to get breakdown report IDs that match the filters
        $filteredReportIds = (clone $baseQuery)->pluck('id');
        
        $eventTypeFrequency = \DB::table('breakdown_events')
            ->selectRaw('
                event_types.name,
                event_types.category,
                COUNT(*) as frequency
            ')
            ->join('event_types', 'breakdown_events.event_type_id', '=', 'event_types.id')
            ->whereIn('breakdown_events.breakdown_report_id', $filteredReportIds)
            ->groupBy('event_types.id', 'event_types.name', 'event_types.category')
            ->orderBy('frequency', 'desc')
            ->limit(10)
            ->get();

        // Get part usage frequency
        $partUsage = \DB::table('breakdown_parts')
            ->selectRaw('
                part_types.name,
                SUM(breakdown_parts.quantity) as total_quantity,
                COUNT(DISTINCT breakdown_parts.breakdown_report_id) as usage_count
            ')
            ->join('part_types', 'breakdown_parts.part_type_id', '=', 'part_types.id')
            ->whereIn('breakdown_parts.breakdown_report_id', $filteredReportIds)
            ->groupBy('part_types.id', 'part_types.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Calculate statistics - use the same base query for consistency
        // Total reports count from filtered base query
        $stats = [
            'total_reports' => (clone $baseQuery)->count(),
            'avg_repair_time' => (clone $baseQuery)->avg(\DB::raw('TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)')),
            'top_machine' => $machineBreakdowns->count() > 0 ? $machineBreakdowns->first()->machine->name : 'N/A',
            'top_event_type' => $eventTypeFrequency->count() > 0 ? $eventTypeFrequency->first()->name : 'N/A',
            'top_part' => $partUsage->count() > 0 ? $partUsage->first()->name : 'N/A',
        ];
        
        // Debug: Log the query results
        \Log::info('Monthly stats count: ' . $monthlyStats->count());
        \Log::info('Machine breakdowns count: ' . $machineBreakdowns->count());
        \Log::info('Event type frequency count: ' . $eventTypeFrequency->count());
        \Log::info('Part usage count: ' . $partUsage->count());
        \Log::info('Stats: ', $stats);

        return view('maintenance.analytics', compact(
            'monthlyStats',
            'machineBreakdowns',
            'eventTypeFrequency',
            'partUsage',
            'stats'
        ));
    }

    /**
     * Filter analytics data
     */
    public function filterAnalytics(Request $request)
    {
        // Create base query for filters
        $baseQuery = BreakdownReport::where('status', 'done');

        // Apply date range filters
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $year = $request->year;

        // Apply date range filter
        if ($dateFrom && $dateTo) {
            $baseQuery->whereBetween('reported_at', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $baseQuery->whereDate('reported_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $baseQuery->whereDate('reported_at', '<=', $dateTo);
        }

        // Apply year filter if no date range
        if (!$dateFrom && !$dateTo && $year) {
            $baseQuery->whereYear('reported_at', $year);
        }

        // Get monthly stats based on filters
        if ($dateFrom && $dateTo) {
            // For date range, group by month within the range
            $monthlyStats = (clone $baseQuery)->selectRaw('
                    YEAR(reported_at) as year,
                    MONTH(reported_at) as month,
                    COUNT(*) as total_reports,
                    AVG(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as avg_repair_time
                ')
                ->groupByRaw('YEAR(reported_at), MONTH(reported_at)')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
        } else {
            // Default: last 12 months from the latest date
            $monthlyStats = (clone $baseQuery)->selectRaw('
                    YEAR(reported_at) as year,
                    MONTH(reported_at) as month,
                    COUNT(*) as total_reports,
                    AVG(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as avg_repair_time
                ')
                ->groupByRaw('YEAR(reported_at), MONTH(reported_at)')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get()
                ->reverse(); // Reverse to show oldest to newest in chart
        }

        // Get machine breakdown frequency with filters and total downtime
        $machineBreakdowns = (clone $baseQuery)->selectRaw('
                machine_id,
                COUNT(*) as breakdown_count,
                SUM(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as total_downtime_minutes
            ')
            ->with('machine')
            ->whereNotNull('repair_start_at')
            ->whereNotNull('repair_end_at')
            ->groupBy('machine_id')
            ->orderBy('breakdown_count', 'desc')
            ->limit(10)
            ->get();

        // Get event type frequency with filters
        // Use subquery to get breakdown report IDs that match the filters
        $filteredReportIds = (clone $baseQuery)->pluck('id');
        
        $eventTypeFrequency = \DB::table('breakdown_events')
            ->selectRaw('
                event_types.name,
                event_types.category,
                COUNT(*) as frequency
            ')
            ->join('event_types', 'breakdown_events.event_type_id', '=', 'event_types.id')
            ->whereIn('breakdown_events.breakdown_report_id', $filteredReportIds)
            ->groupBy('event_types.id', 'event_types.name', 'event_types.category')
            ->orderBy('frequency', 'desc')
            ->limit(10)
            ->get();

        // Get part usage frequency
        $partUsage = \DB::table('breakdown_parts')
            ->selectRaw('
                part_types.name,
                SUM(breakdown_parts.quantity) as total_quantity,
                COUNT(DISTINCT breakdown_parts.breakdown_report_id) as usage_count
            ')
            ->join('part_types', 'breakdown_parts.part_type_id', '=', 'part_types.id')
            ->whereIn('breakdown_parts.breakdown_report_id', $filteredReportIds)
            ->groupBy('part_types.id', 'part_types.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Calculate statistics
        // For total reports, we should count all reports (not just done) to match the dashboard
        $totalReportsQuery = BreakdownReport::query();
        
        // Apply same filters but without status restriction
        if ($dateFrom && $dateTo) {
            $totalReportsQuery->whereBetween('reported_at', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $totalReportsQuery->whereDate('reported_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $totalReportsQuery->whereDate('reported_at', '<=', $dateTo);
        }

        if (!$dateFrom && !$dateTo && $year) {
            $totalReportsQuery->whereYear('reported_at', $year);
        }
        
        // For avg_repair_time, create a new query without groupBy
        $avgRepairTimeQuery = BreakdownReport::where('status', 'done');
        
        // Apply same date filters
        if ($dateFrom && $dateTo) {
            $avgRepairTimeQuery->whereBetween('reported_at', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $avgRepairTimeQuery->whereDate('reported_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $avgRepairTimeQuery->whereDate('reported_at', '<=', $dateTo);
        }

        if (!$dateFrom && !$dateTo && $year) {
            $avgRepairTimeQuery->whereYear('reported_at', $year);
        }
        
        $stats = [
            'total_reports' => $totalReportsQuery->count(),
            'avg_repair_time' => $avgRepairTimeQuery->avg(\DB::raw('TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)')),
            'top_machine' => $machineBreakdowns->count() > 0 ? $machineBreakdowns->first()->machine->name : 'N/A',
            'top_event_type' => $eventTypeFrequency->count() > 0 ? $eventTypeFrequency->first()->name : 'N/A',
            'top_part' => $partUsage->count() > 0 ? $partUsage->first()->name : 'N/A',
        ];

        return response()->json([
            'monthlyStats' => $monthlyStats,
            'machineBreakdowns' => $machineBreakdowns,
            'eventTypeFrequency' => $eventTypeFrequency,
            'partUsage' => $partUsage,
            'stats' => $stats,
        ]);
    }

    /**
     * Export reports to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = BreakdownReport::with(['machine', 'reporter']);

        // Apply filters same as reports method
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('reported_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('reported_at', '<=', $request->date_to);
        }

        $reports = $query->orderBy('reported_at', 'desc')->get();

        // Create Excel export using Laravel Excel 3.x
        $export = new class($reports) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $reports;

            public function __construct($reports)
            {
                $this->reports = $reports;
            }

            public function collection()
            {
                return $this->reports->map(function ($report) {
                    return [
                        'ID' => $report->id,
                        'Tanggal' => $report->reported_at->format('d/m/Y H:i'),
                        'Mesin' => $report->machine->name,
                        'Pelapor' => $report->reporter->name,
                        'Status' => $report->status,
                        'Deskripsi' => $report->description,
                        'Mulai Perbaikan' => $report->repair_start_at ? $report->repair_start_at->format('d/m/Y H:i') : '-',
                        'Selesai Perbaikan' => $report->repair_end_at ? $report->repair_end_at->format('d/m/Y H:i') : '-',
                        'Catatan Teknisi' => $report->technician_notes ?? '-',
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'ID',
                    'Tanggal',
                    'Mesin',
                    'Pelapor',
                    'Status',
                    'Deskripsi',
                    'Mulai Perbaikan',
                    'Selesai Perbaikan',
                    'Catatan Teknisi'
                ];
            }
        };

        return \Excel::download($export, 'breakdown_reports_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = BreakdownReport::with(['machine', 'reporter']);

        // Apply filters same as reports method
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('reported_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('reported_at', '<=', $request->date_to);
        }

        $reports = $query->orderBy('reported_at', 'desc')->get();

        $pdf = \PDF::loadView('exports.reports_pdf', compact('reports'));
        return $pdf->download('breakdown_reports_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export single report to PDF
     */
    public function exportSinglePdf(BreakdownReport $breakdownReport)
    {
        $breakdownReport->load([
            'machine',
            'reporter',
            'eventTypes',
            'causeTypes',
            'breakdownParts.partType',
            'breakdownResponsibility',
            'maintenanceLeader'
        ]);

        $pdf = \PDF::loadView('exports.single_report_pdf', compact('breakdownReport'));
        return $pdf->download('breakdown_report_' . $breakdownReport->id . '_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export analytics to PDF
     */
    public function exportAnalyticsPdf(Request $request)
    {
        // Debug: Log the request parameters
        \Log::info('Export Analytics PDF Request:', $request->all());
        
        // Create base query for filters
        $baseQuery = BreakdownReport::where('status', 'done');

        // Apply date range filters
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $year = $request->year;

        // Debug: Log filter values
        \Log::info('Filter values:', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'year' => $year
        ]);

        // Apply date range filter
        if ($dateFrom && $dateTo) {
            $baseQuery->whereBetween('reported_at', [$dateFrom, $dateTo]);
            \Log::info('Applied date range filter:', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $baseQuery->whereDate('reported_at', '>=', $dateFrom);
            \Log::info('Applied date_from filter:', [$dateFrom]);
        } elseif ($dateTo) {
            $baseQuery->whereDate('reported_at', '<=', $dateTo);
            \Log::info('Applied date_to filter:', [$dateTo]);
        }

        // Apply year filter if no date range
        if (!$dateFrom && !$dateTo && $year) {
            $baseQuery->whereYear('reported_at', $year);
            \Log::info('Applied year filter:', [$year]);
        }

        // Get monthly breakdown statistics - create fresh query from base
        // Get last 12 months from the latest date
        $monthlyStats = (clone $baseQuery)->selectRaw('
                YEAR(reported_at) as year,
                MONTH(reported_at) as month,
                COUNT(*) as total_reports,
                AVG(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as avg_repair_time
            ')
            ->groupByRaw('YEAR(reported_at), MONTH(reported_at)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse(); // Reverse to show oldest to newest in chart

        // Get machine breakdown frequency - create fresh query from base
        $machineBreakdowns = (clone $baseQuery)->selectRaw('
                machine_id,
                COUNT(*) as breakdown_count,
                SUM(TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)) as total_downtime_minutes
            ')
            ->with('machine')
            ->whereNotNull('repair_start_at')
            ->whereNotNull('repair_end_at')
            ->groupBy('machine_id')
            ->orderBy('breakdown_count', 'desc')
            ->limit(10)
            ->get();

        // Get event type frequency
        // Use subquery to get breakdown report IDs that match the filters
        $filteredReportIds = (clone $baseQuery)->pluck('id');
        
        $eventTypeFrequency = \DB::table('breakdown_events')
            ->selectRaw('
                event_types.name,
                event_types.category,
                COUNT(*) as frequency
            ')
            ->join('event_types', 'breakdown_events.event_type_id', '=', 'event_types.id')
            ->whereIn('breakdown_events.breakdown_report_id', $filteredReportIds)
            ->groupBy('event_types.id', 'event_types.name', 'event_types.category')
            ->orderBy('frequency', 'desc')
            ->limit(10)
            ->get();

        // Get part usage frequency
        $partUsage = \DB::table('breakdown_parts')
            ->selectRaw('
                part_types.name,
                SUM(breakdown_parts.quantity) as total_quantity,
                COUNT(DISTINCT breakdown_parts.breakdown_report_id) as usage_count
            ')
            ->join('part_types', 'breakdown_parts.part_type_id', '=', 'part_types.id')
            ->whereIn('breakdown_parts.breakdown_report_id', $filteredReportIds)
            ->groupBy('part_types.id', 'part_types.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Calculate statistics - use the same base query for consistency
        // Total reports count from filtered base query
        $stats = [
            'total_reports' => (clone $baseQuery)->count(),
            'avg_repair_time' => (clone $baseQuery)->avg(\DB::raw('TIMESTAMPDIFF(MINUTE, repair_start_at, repair_end_at)')),
            'top_machine' => $machineBreakdowns->count() > 0 ? $machineBreakdowns->first()->machine->name : 'N/A',
            'top_event_type' => $eventTypeFrequency->count() > 0 ? $eventTypeFrequency->first()->name : 'N/A',
            'top_part' => $partUsage->count() > 0 ? $partUsage->first()->name : 'N/A',
        ];

        // Generate chart images and convert to base64
        $chartImages = [];
        
        try {
            // Generate monthly bar chart
            if ($monthlyStats->count() > 0) {
                $monthlyChartPath = \App\Helpers\ChartGenerator::generateMonthlyBarChart($monthlyStats, 800, 400);
                if ($monthlyChartPath && file_exists($monthlyChartPath)) {
                    $chartImages['monthly'] = 'data:image/png;base64,' . base64_encode(file_get_contents($monthlyChartPath));
                    // Clean up temporary file
                    @unlink($monthlyChartPath);
                }
            }
            
            // Generate machine breakdown chart
            if ($machineBreakdowns->count() > 0) {
                $machineChartPath = \App\Helpers\ChartGenerator::generateMachineBreakdownChart($machineBreakdowns, 800, 400);
                if ($machineChartPath && file_exists($machineChartPath)) {
                    $chartImages['machine'] = 'data:image/png;base64,' . base64_encode(file_get_contents($machineChartPath));
                    // Clean up temporary file
                    @unlink($machineChartPath);
                }
            }
            
            // Generate event type chart
            if ($eventTypeFrequency->count() > 0) {
                $eventChartPath = \App\Helpers\ChartGenerator::generateEventTypeChart($eventTypeFrequency, 800, 400);
                if ($eventChartPath && file_exists($eventChartPath)) {
                    $chartImages['event'] = 'data:image/png;base64,' . base64_encode(file_get_contents($eventChartPath));
                    // Clean up temporary file
                    @unlink($eventChartPath);
                }
            }
            
            // Generate part usage chart
            if ($partUsage->count() > 0) {
                $partChartPath = \App\Helpers\ChartGenerator::generatePartUsageChart($partUsage, 600, 400);
                if ($partChartPath && file_exists($partChartPath)) {
                    $chartImages['part'] = 'data:image/png;base64,' . base64_encode(file_get_contents($partChartPath));
                    // Clean up temporary file
                    @unlink($partChartPath);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't crash the PDF generation
            \Log::error('Chart generation error in PDF export: ' . $e->getMessage());
            // Continue without charts
        }

        $data = [
            'monthlyStats' => $monthlyStats,
            'machineBreakdowns' => $machineBreakdowns,
            'eventTypeFrequency' => $eventTypeFrequency,
            'partUsage' => $partUsage,
            'stats' => $stats,
            'chartImages' => $chartImages,
            'filters' => [
                'year' => $year,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];

        $pdf = \PDF::loadView('exports.analytics_pdf', $data);
        
        // Clean up temporary chart files after PDF is generated
        \App\Helpers\ChartGenerator::cleanupTempFiles(5); // Clean up files older than 5 minutes
        
        return $pdf->download('analytics_report_' . date('Ymd_His') . '.pdf');
    }
}
