<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\CustomersImport;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('Admin.index', compact('customers'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'customer_upload' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CustomersImport, $request->file('customer_upload'));

        return back()->with('success', 'Customers imported successfully.');
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

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

    // CustomerController.php

    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:Activated,Deactivated',
    //     ]);

    //     $customer = Customer::findOrFail($id);
    //     $customer->status = $request->status;
    //     $customer->save();

    //     return response()->json(['message' => 'Status updated successfully.']);
    // }

}
