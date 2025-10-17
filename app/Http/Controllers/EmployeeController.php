<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show all employees ordered by employee_id ascending
        $employees = Employee::orderBy('employee_id', 'asc')->get(); // Changed paginate(10) to get()
        return view('Employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('role_name')->get();
        return view('Employee.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active'),
        ]);
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'required|email|unique:employees,email|unique:users,email',
            'role_id'     => 'required|exists:roles,role_id',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active')
            ? $request->boolean('is_active')
            : true;

        // 🧱 Step 1: Create the User account first
        $user = \App\Models\User::create([
            'name'     => "{$validated['first_name']} {$validated['last_name']}",
            'email'    => "{$validated['first_name']}.{$validated['last_name']}@MGS.com",
            'password' => bcrypt('password123'), // 🔒 Default password (you can customize)
            'role_id'  => $validated['role_id'],
        ]);

        // 🧱 Step 2: Create the Employee record and link to user
        $employee = \App\Models\Employee::create([
            'user_id'     => $user->id,
            'first_name'  => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name'   => $validated['last_name'],
            'phone'       => $validated['phone'] ?? null,
            'email'       => $validated['email'],
            'is_active'   => $validated['is_active'],
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee and user account created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('Employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $roles = \App\Models\Role::orderBy('role_name')->get();
        return view('Employee.edit', compact('employee', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'required|email|unique:employees,email,' . $employee->employee_id . ',employee_id|unique:users,email,' . $employee->user_id . ',id',
            'role_id'     => 'required|exists:roles,role_id',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        // Update employee record
        $employee->update([
            'first_name'  => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name'   => $validated['last_name'],
            'phone'       => $validated['phone'] ?? null,
            'email'       => $validated['email'],
            'is_active'   => $validated['is_active'],
        ]);

        // Update linked user
        if ($employee->user) {
            $employee->user->update([
                'name'    => "{$validated['first_name']} {$validated['last_name']}",
                'email'   => "{$validated['first_name']}.{$validated['last_name']}@MGS.com",
                'role_id' => $validated['role_id'],
            ]);
        }

        return redirect()->route('employees.index')->with('success', 'Employee and user details updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
