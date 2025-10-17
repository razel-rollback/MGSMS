<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::paginate(10); // Fetch suppliers with pagination
        return view('Supplier.supplier-index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Supplier.supplier-add'); // Show the add supplier form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        try {
            Supplier::create($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return redirect()->back()->withInput()->withErrors(['email' => 'The email address is already in use.']);
            }

            // Handle other database exceptions
            return redirect()->back()->withInput()->withErrors(['error' => 'An unexpected error occurred. Please try again.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('Supplier.supplier-add', compact('supplier')); // Reuse same form for edit
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->supplier_id . ',supplier_id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        try {
            $supplier->update($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return redirect()->back()->withInput()->withErrors(['email' => 'The email address is already in use.']);
            }

            // Handle other database exceptions
            return redirect()->back()->withInput()->withErrors(['error' => 'An unexpected error occurred. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully!');
    }
}
