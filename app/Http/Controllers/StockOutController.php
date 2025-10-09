<?php

namespace App\Http\Controllers;

use App\Models\StockOutRequest;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOutRequest::latest()->paginate(10);
        return view('Stock_out.index', compact('stockOuts')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_order_id' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'nullable|string'
        ]);

        $stockOut = StockOutRequest::create($request->all());

        return response()->json(['success' => 'Stock Out Request Created', 'data' => $stockOut]);
    }

    public function show(StockOutRequest $stockOut)
    {
        return response()->json($stockOut);
    }

    public function edit(StockOutRequest $stockOut)
    {
        return response()->json($stockOut);
    }

    public function update(Request $request, StockOutRequest $stockOut)
    {
        $request->validate([
            'job_order_id' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'nullable|string'
        ]);

        $stockOut->update($request->all());

        return response()->json(['success' => 'Stock Out Request Updated', 'data' => $stockOut]);
    }

    public function destroy(StockOutRequest $stockOut)
    {
        $stockOut->delete();

        return response()->json(['success' => 'Stock Out Request Deleted']);
    }
}