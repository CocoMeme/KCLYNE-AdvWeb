<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\CustomersImport;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    // Display the customer management index page
    public function index()
    {
        $customers = Customer::all();
        return view('Admin.index', compact('customers'));
    }

    // Import customers from an Excel file
    public function import(Request $request)
    {
        $request->validate([
            'customer_upload' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CustomersImport, $request->file('customer_upload'));

        return back()->with('success', 'Customers imported successfully.');
    }

    // Export customers to an Excel file
    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    // Update the status of a customer
    public function updateStatus(Request $request, $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->status = $request->status;
            $customer->save();
            return response()->json(['success' => 'Status updated successfully.']);
        }

        return response()->json(['error' => 'Customer not found.'], 404);
    }
}
