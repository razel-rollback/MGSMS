<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockReport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StockReportExport;

class StockReportController extends Controller
{
    /**
     * Display the main stock report dashboard.
     */
    public function index(Request $request)
    {
        $query = StockReport::query();

        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10);

        return view('Stock_reports.stock_report', compact('reports'));
    }

    /**
     * Generate and display a detailed stock report.
     */
// In ReportController.php
        public function generate(Request $request)
        {
            $reportType = $request->input('report_type');
            $from = $request->input('from');
            $to = $request->input('to');
            $filter = $request->input('filter');

            $items = InventoryItem::query()
                ->when($filter === 'Current Stock Level', function ($query) {
                    $query->orderBy('current_stock', 'desc');
                })
                ->whereBetween('created_at', [$from, $to])
                ->get();

            return view('reports.stock', compact('items', 'reportType', 'from', 'to', 'filter'));
        }
    /**
     * Export stock report to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new StockReportExport, 'stock_report.xlsx');
    }

    /**
     * Export stock report to PDF.
     */
    public function exportPDF()
    {
        $reportData = StockReport::with('items')->latest()->get();
        $pdf = Pdf::loadView('Stock_reports.stock_view', compact('reportData'));
        return $pdf->download('stock_report.pdf');
    }
}