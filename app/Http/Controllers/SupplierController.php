<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50|regex:/^[0-9+\-\s()]+$/',
            'tin' => 'nullable|string|max:50|regex:/^[0-9-]+$/',
        ], [
            'name.required' => 'Supplier name is required.',
            'name.unique' => 'A supplier with this name already exists.',
            'contact_phone.regex' => 'Please enter a valid phone number.',
            'tin.regex' => 'Please enter a valid TIN number (numbers and dashes only).',
        ]);

        $supplier = Supplier::create($validated);
        ActivityLog::log('supplier.created', $supplier);

        return redirect()->route('suppliers.index')
            ->with('message', "Supplier {$supplier->name} created successfully.");
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50|regex:/^[0-9+\-\s()]+$/',
            'tin' => 'nullable|string|max:50|regex:/^[0-9-]+$/',
        ], [
            'name.required' => 'Supplier name is required.',
            'name.unique' => 'A supplier with this name already exists.',
            'contact_phone.regex' => 'Please enter a valid phone number.',
            'tin.regex' => 'Please enter a valid TIN number (numbers and dashes only).',
        ]);

        $supplier->update($validated);
        ActivityLog::log('supplier.updated', $supplier);

        return redirect()->route('suppliers.index')
            ->with('message', "Supplier {$supplier->name} updated successfully.");
    }

    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->delete();
        ActivityLog::log('supplier.deleted', $supplier);

        return redirect()->route('suppliers.index')
            ->with('message', "Supplier {$name} deleted successfully.");
    }
}