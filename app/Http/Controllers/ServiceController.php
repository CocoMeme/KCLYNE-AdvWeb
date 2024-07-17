<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ServiceImport;
use App\Exports\ServiceExport;


class ServiceController extends Controller
{
    public function index() {
        return view('Service.index');
    }

    public function get_service($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'error' => 'Employee not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'success' => 'Employee retrieved successfully',
            'employee' => $service,
            'status' => 200
        ]);
    }
    //API READ ALL SERVICES
    public function get_all_service(Request $request)
    {
        $service = Service::all();
        return response()->json($service);
    }

    public function fetchAll(Request $request)
    {
        $services = Service::all();
        $output = '';
        
        if ($services->count() > 0) {
            $output .= '<table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Image</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($services as $service) {
                $imagePath = asset('images/Services/' . $service->service_image);
                $output .= '<tr>
                    <td>' . $service->id . '</td>
                    <td><img src="' . $imagePath . '" width="50" class="img-thumbnail rounded-circle"></td>
                    <td>' . $service->service_name .'</td>
                    <td>' . $service->description . '</td>
                    <td>' . $service->price . '</td>
                    <td>
                        <a href="#" id="' . $service->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editServiceModal"><i class="bi-pencil-square h4"></i></a>
                        <a href="#" id="' . $service->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                    </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No records in the database!</h1>';
        }
    }    

    //UI CREATE
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Handle image upload
        if ($request->hasFile('service_image')) {
            $fileName = time() . '.' . $request->service_image->extension();
            $request->service_image->move(public_path('images/Services'), $fileName);
            $validatedData['service_image'] = $fileName;
        } elseif ($request->has('service_image')) {
            // Decode the base64 image string if present
            $imageData = $request->service_image;
            $image = base64_decode($imageData);
            $fileName = time() . '.png';
            Storage::put('public/Images/Services/' . $fileName, $image);
            $validatedData['service_image'] = $fileName;
        }
    
        // Create service
        $service = Service::create($validatedData);
    
        return response()->json([
            'status' => 200,
            'message' => 'Service Created Successfully!',
            'service' => $service,
        ]);
    }
    
    //API STORE
    public function apistore(Request $request) {
        $validatedData = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'service_image' => 'nullable|string',
        ]);
    
        if ($request->hasFile('service_image')) {
            $fileName = time() . '.' . $request->service_image->extension();
            $request->service_image->move(public_path('images/Services'), $fileName);
            $validatedData['service_image'] = $fileName;
        } elseif ($request->has('service_image')) {
            $imageData = $request->service_image;
            $image = base64_decode($imageData);
            $fileName = time() . '.png';
            Storage::put('public/Images/Services/' . $fileName, $image);
            $validatedData['service_image'] = $fileName;
        }
        $service = Service::create($validatedData);
    
        return response()->json([
            'status' => 200,
            'message' => 'Service Created Successfully!',
            'service' => $service,
        ]);
    }

     public function edit(Request $request) {
        $id = $request->id;
        $emp = Service::find($id);
        return response()->json($emp);
    }
 
    public function update(Request $request)
{
    $request->validate([
        'service_name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
        'price' => 'sometimes|required|numeric',
        'service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $service = Service::find($request->emp_id);
    if ($request->hasFile('service_image')) {
        $file = $request->file('service_image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/Services'), $fileName);
        if ($service->service_image) {
            Storage::delete('public/Images/Services/' . $service->service_image);
        }

        $service->service_image = $fileName;
    } elseif ($request->has('service_image')) {
        $imageData = $request->service_image;
        $image = base64_decode($imageData);
        $fileName = time() . '.png';
        Storage::put('public/Images/Services/' . $fileName, $image);
        if ($service->service_image) {
            Storage::delete('public/Images/Services/' . $service->service_image);
        }

        $service->service_image = $fileName;
    }

    $service->service_name = $request->service_name;
    $service->description = $request->description;
    $service->price = $request->price;

    $service->save();

    return response()->json([
        'status' => 200,
        'message' => 'Service Updated Successfully!',
        'service' => $service,
    ]);
}

    public function apiupdate(Request $request, $id)
{
    $request->validate([
        'service_name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
        'price' => 'sometimes|required|numeric',
        'service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ], [
        'service_name.unique' => 'The service name already exists.',
        'description.string' => 'The description must be a string.',
        'price.numeric' => 'The price must be a number.',
    ]);

    $service = Service::find($id);

    if (!$service) {
        return response()->json([
            'status' => 404,
            'message' => 'Service not found',
        ]);
    }

    if ($request->hasFile('service_image')) {
        $file = $request->file('service_image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/Services'), $fileName);
        if ($service->service_image) {
            Storage::delete('public/Images/Services/' . $service->service_image);
        }

        $service->service_image = $fileName;
    } elseif ($request->has('service_image') && !is_null($request->service_image)) {
        $imageData = $request->service_image;
        $image = base64_decode($imageData);
        $fileName = time() . '.png';
        Storage::put('public/Images/Services/' . $fileName, $image);
        if ($service->service_image) {
            Storage::delete('public/Images/Services/' . $service->service_image);
        }

        $service->service_image = $fileName;
    }
    if ($request->filled('service_name')) {
        $service->service_name = $request->service_name;
    }
    if ($request->filled('description')) {
        $service->description = $request->description;
    }
    if ($request->filled('price')) {
        $service->price = $request->price;
    }

    $service->save();

    return response()->json([
        'status' => 200,
        'message' => 'Service Updated Successfully!',
        'service' => $service,
    ]);
}

    public function delete(Request $request) {
        $id = $request->id;
        $emp = Service::find($id);
        if (Storage::delete('public/images/' . $emp->service_image)) {
            Service::destroy($id);
        }
    }

    public function apidelete($id) {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['success' => 'Service deleted successfully'], 200);
    }

    public function export() {
        return Excel::download(new ServiceExport, 'ServiceExport.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);
    
        $file = $request->file('excel_file');
        Excel::import(new ServiceImport, $file);
    
        return response()->json(['status' => 200, 'message' => 'Services Imported Successfully!']);
    }

    public $rowperpage = 10; // Number of rows per page

    public function customer_service_index()
{
    // Number of rows per page
    $data['rowperpage'] = $this->rowperpage;

    // Total number of records
    $data['totalrecords'] = Service::count();

    // Fetch initial set of services
    $data['services'] = Service::take($this->rowperpage)->get();

    // Load index view
    return view('customer.service_index', $data);
}


public function getcustomer_service_index(Request $request)
{
    $start = $request->get("start");
    $rowperpage = $this->rowperpage;

    // Fetch additional services
    $services = Service::skip($start)->take($rowperpage)->get();

    $html = "";
    foreach ($services as $service) {
        $service_name = $service->service_name;
        $description = $service->description;
        $price = $service->price;
        $service_image = $service->service_image;

        $html .= '<div class="card w-75 post mb-3">
                    <div class="card-body">
                        <h5 class="card-title">' . $service_name . '</h5>
                        <p class="card-text">' . $description . '</p>
                        <p class="card-text">$' . $price . '</p>
                    </div>
                    <img src="' . asset('images/Services/' . $service_image) . '" class="card-img-bottom" alt="' . $service_name . '">
                </div>';
    }

    return response()->json(['html' => $html]);
}
}