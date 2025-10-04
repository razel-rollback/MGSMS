<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index()
    {
        return view('Stock_reports.stock_report');
    }

    public function generate()
    {
        return view('Stock_reports.stock_view');
    }
}
