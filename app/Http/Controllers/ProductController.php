<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use DB;
use Storage;

class ProductController extends Controller
{
    //API CREATE PRODUCT
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $imagePaths = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('images/Products'), $imageName);
                $imagePaths[] = $imageName;
            }
            $validatedData['image_path'] = implode(',', $imagePaths);
        }
    
        $product = Product::create($validatedData);
    
        return response()->json([
            "success" => "Product created successfully.",
            "product" => $product,
            "status" => 200
        ]);
    }
    


    //API READ PRODUCT
    public function get_product($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'error' => 'Product not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'success' => 'Product retrieved successfully',
            'product' => $product,
            'status' => 200
        ]);
    }

    //API READ ALL PRODUCTS
    public function get_all_products(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }

    //API UPDATE PRODUCT
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json([
                'error' => 'Product not found',
                'status' => 404
            ], 404);
        }
    
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'sometimes|required|numeric',
            'stock_quantity' => 'sometimes|required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate multiple images
        ]);
    
        $imagePaths = [];
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            if ($product->image_path) {
                $oldImages = explode(',', $product->image_path);
                foreach ($oldImages as $oldImage) {
                    Storage::delete('images/Products/'.$oldImage);
                }
            }
    
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/Products'), $imageName);
                $imagePaths[] = $imageName;
            }
    
            $validatedData['image_path'] = implode(',', $imagePaths); // Save filenames as a comma-separated string
        }
    
        $product->update($validatedData);
    
        return response()->json([
            'success' => 'Product updated successfully.',
            'product' => $product,
            'status' => 200
        ]);
    }
    

    //API DELETE PRODUCT
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->image) {
            Storage::delete('images/Products/'.$product->image);
        }

        $product->delete();

        return response()->json(['success' => 'Product deleted successfully'], 200);
    }

    //CLASSES

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function show($id)
    {
        return Product::find($id);
    }

    public function import(Request $request)
    {
        $request->validate([
            'product_upload' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ProductImport(), $request->file('product_upload'));

        return redirect()->back()->with('success', 'Excel file Imported Successfully');
    }

    public function export()
    {
        return Excel::download(new ProductExport, 'ProductsExport.xlsx');
    }
}
