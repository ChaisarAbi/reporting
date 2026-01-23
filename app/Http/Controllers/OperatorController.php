<?php

namespace App\Http\Controllers;

use App\Models\BreakdownReport;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    /**
     * Display dashboard for operator
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        // Query untuk statistik (tanpa pagination, semua data)
        $statsQuery = BreakdownReport::where('reporter_id', $user->id);
        
        // Apply filters untuk statistik
        if ($request->filled('status')) {
            $statsQuery->where('status', $request->status);
        }
        
        if ($request->filled('machine_id')) {
            $statsQuery->where('machine_id', $request->machine_id);
        }
        
        $stats = [
            'total_reports' => $statsQuery->count(),
            'new_reports' => $statsQuery->clone()->where('status', 'new')->count(),
            'in_progress_reports' => $statsQuery->clone()->where('status', 'in_progress')->count(),
            'done_reports' => $statsQuery->clone()->where('status', 'done')->count(),
        ];

        // Query untuk reports dengan pagination
        $reportsQuery = BreakdownReport::where('reporter_id', $user->id);
        
        // Apply filters untuk reports
        if ($request->filled('status')) {
            $reportsQuery->where('status', $request->status);
        }
        
        if ($request->filled('machine_id')) {
            $reportsQuery->where('machine_id', $request->machine_id);
        }
        
        $reports = $reportsQuery->with('machine')
            ->orderBy('reported_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());
            
        // Get all machines for filter dropdown
        $machines = Machine::orderBy('name')->get();

        return view('operator.dashboard', compact('reports', 'stats', 'machines'));
    }

    /**
     * Show the form for creating a new breakdown report
     */
    public function create()
    {
        $machines = Machine::all();
        return view('operator.create', compact('machines'));
    }

    /**
     * Store a newly created breakdown report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'machine_id' => 'required|exists:machines,id',
            'department' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'shift' => 'required|in:1,2',
            'machine_number' => 'nullable|string|max:255',
            'problem_area' => 'required|string',
        ]);

        $validated['reporter_id'] = Auth::id();

        BreakdownReport::create($validated);

        return redirect()->route('operator.dashboard')
            ->with('success', 'Laporan kerusakan berhasil dibuat dan dikirim ke Leader Teknisi.');
    }

    /**
     * Display the specified breakdown report
     */
    public function show(BreakdownReport $breakdownReport)
    {
        // Authorization check - user can only see their own reports
        if ($breakdownReport->reporter_id !== Auth::id()) {
            abort(403);
        }

        $breakdownReport->load([
            'machine',
            'eventTypes',
            'causeTypes',
            'breakdownParts.partType',
            'breakdownResponsibility',
            'maintenanceLeader'
        ]);

        return view('operator.show', compact('breakdownReport'));
    }

    /**
     * Show reports with filtering
     */
    public function reports(Request $request)
    {
        $user = Auth::user();
        $query = BreakdownReport::where('reporter_id', $user->id)
            ->with('machine');

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
        $machines = Machine::all();

        return view('operator.reports', compact('reports', 'machines'));
    }

    /**
     * Export reports to Excel
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $query = BreakdownReport::where('reporter_id', $user->id)
            ->with('machine');

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
        $user = Auth::user();
        $query = BreakdownReport::where('reporter_id', $user->id)
            ->with('machine');

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
        // Authorization check - user can only export their own reports
        if ($breakdownReport->reporter_id !== Auth::id()) {
            abort(403);
        }

        $breakdownReport->load([
            'machine',
            'eventTypes',
            'causeTypes',
            'breakdownParts.partType',
            'breakdownResponsibility',
            'maintenanceLeader'
        ]);

        $pdf = \PDF::loadView('exports.single_report_pdf', compact('breakdownReport'));
        return $pdf->download('breakdown_report_' . $breakdownReport->id . '_' . date('Ymd_His') . '.pdf');
    }
}
