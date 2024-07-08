<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;

use App\Imports\EmployeeImport;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

use Yajra\DataTables\DataTables;
use DB;

class EmployeeController extends Controller
{
    //API CREATE EMPLOYEE
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'phone' => 'required|string|max:15',
            'house_no' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'baranggay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'payrate_per_hour' => 'required|numeric',
            'employee_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('employee_image')) {
            $imageName = time().'.'.$request->employee_image->extension();  
            $request->employee_image->move(public_path('images/Employees'), $imageName);
            $validatedData['employee_image'] = $imageName;
        } else {
            if ($request->sex === 'Male') {
                $validatedData['employee_image'] = 'defaultmale.png';
            } elseif ($request->sex === 'Female') {
                $validatedData['employee_image'] = 'defaultfemale.png';
            }
        }
        $employee = Employee::create($validatedData);
    
        return response()->json([
            "success" => "Employee created successfully.",
            "employee" => $employee,
            "status" => 200
        ]);
    }    
    //API READ EMPLOYEE
    public function get_employee($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'error' => 'Employee not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'success' => 'Employee retrieved successfully',
            'employee' => $employee,
            'status' => 200
        ]);
    }
    //API READ ALL EMPLOYEE
    public function get_all_employee(Request $request){
        $employee=Employee::all();
        return response()->json($employee);
    }
    //API UPDATE EMPLOYEE STATUS
    public function updateStatus($id, Request $request)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = $request->status;
        $employee->save();

        return response()->json([
            'success' => 'Employee status updated successfully.',
            'status' => 200
        ]);
    }
    //API UPDATE EMPLOYEE
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'error' => 'Employee not found',
                'status' => 404
            ], 404);
        }

        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'sex' => 'sometimes|required|in:Male,Female',
            'phone' => 'sometimes|required|string|max:15',
            'house_no' => 'sometimes|required|string|max:255',
            'street' => 'sometimes|required|string|max:255',
            'baranggay' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'province' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'payrate_per_hour' => 'sometimes|required|numeric',
            'employee_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('employee_image')) {
            $imageName = time().'.'.$request->employee_image->extension();  
            $request->employee_image->move(public_path('images/Employees'), $imageName);
            $validatedData['employee_image'] = $imageName;
        } elseif (!$employee->employee_image) {
            $validatedData['employee_image'] = $validatedData['sex'] === 'Male' ? 'defaultmale.png' : 'defaultfemale.png';
        }

        $employee->update($validatedData);
        
        return response()->json([
            'success' => 'Employee updated successfully.',
            'employee' => $employee,
            'status' => 200
        ]);
    }
    //API DELETE EMPLOYEE
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json(['success' => 'Employee deleted successfully'], 200);
    }

    //CLASSES

    public function index()
    {
        $employees = Employee::all();
        return view('employee.index', compact('employees'));
    }   

    public function create(){
        return view('employee.create');
    }

    public function edit($id)
    {
        $employee = DB::table('employees')->where('id', $id)->first();
        return view('employee.edit', compact('employee'));
    }

    public function show($id)
    {
        return Employee::find($id);
    }

    public function import(Request $request)
    {
        $request->validate([
            'employee_upload' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new EmployeeImport(), $request->file('employee_upload'));

        return redirect()->back()->with('success', 'Excel file Imported Successfully');
    }

    public function export()
    {
        return Excel::download(new EmployeeExport, 'EmployeesExport.xlsx');
    }
}

