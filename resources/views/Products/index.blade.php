@extends('layouts.app')

@section('content')

<div class="admin-container">
    <div class="left-panel">
        <h3 align="center">Product Details</h3>
        <img id="productImage" src="Images\Layouts\Logo.jpg" alt="Product Image" class="product-image">
        <br>
        <br>
        <p id="productName" align="center">Name</p>
        <p id="productDescription" align="center">Description</p>
        <p id="productPrice" align="center">Price</p>
        <p id="productStock" align="center">Stock</p>
    </div>

    <div class="right-panel">

        <div id="products">

            <div class="table-methods">
                <a class="btn btn-primary" href="#" role="button" id="openCreateProductModal"><i class='bx bxs-add-to-queue' ></i>Add Product</a>
                <a class="btn btn-info btn-primary" href="#" role="button" id="openImportProductModal"><i class='bx bxs-file-import'></i>Import Excel File</a>

                <form method="POST" action="{{ route('product.export') }}">
                    @csrf
                    <button type="submit" class="btn btn-info btn-primary"><i class='bx bxs-file-export'></i>Export Excel File</button>
                </form>
            </div>

            <div class="table-responsive">
                <table id="ptable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php
                                $imagePaths = explode(',', $product->image_path);
                            @endphp
                            <tr data-product-images="{{ $product->image_path }}" data-product-description="{{ $product->description }}" data-product-price="{{ $product->price }}" data-product-stock="{{ $product->stock_quantity }}">
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if (!empty($product->image_path))
                                        @foreach ($imagePaths as $imagePath)
                                            <img src="{{ url('images/Products/'.$imagePath) }}" alt="Product image" width="50" height="50" class="img-thumbnail">
                                        @endforeach
                                    @else
                                        <img src="#" alt="Product image" width="50" height="50" class="img-thumbnail">
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-button" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}" data-stock-quantity="{{ $product->stock_quantity }}"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn btn-danger btn-sm delete-button" data-id="{{ $product->id }}"><i class="fa-solid fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- Delete Confirmation Modal -->
        <div class="create-product-modal" id="deleteModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="close" id="closeDeleteModal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the selected product?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-primary">Yes</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteModal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

<!-- Edit Product Modal -->
<div class="create-product-modal" id="editProductModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Product</h5>
            <button type="button" class="close" id="closeEditProductModal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editProductId" name="id">
                <div class="form-group">
                    <label for="editName">Product Name</label>
                    <input type="text" class="form-control" id="editName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="editPrice">Price</label>
                    <input type="number" class="form-control" id="editPrice" name="price" required>
                </div>
                <div class="form-group">
                    <label for="editStockQuantity">Stock Quantity</label>
                    <input type="number" class="form-control" id="editStockQuantity" name="stock_quantity" required>
                </div>
                <div class="form-group">
                    <label for="editImages">Product Images</label>
                    <input type="file" class="form-control-file" id="editImages" name="images[]" multiple>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" id="closeEditProductModalFooter">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Create Product Modal -->
<div class="create-product-modal" id="createProductModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Create Product</h5>
            <button type="button" class="close" id="closeCreateProductModal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                @csrf
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                </div>
                <div class="form-group">
                    <label for="images">Product Images</label>
                    <input type="file" class="form-control-file" id="images" name="images[]" multiple>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" id="closeCreateProductModalFooter">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


        <!-- Import Product Modal -->
        <div class="create-product-modal" id="importProductModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importProductModalLabel">Import Products</h5>
                    <button type="button" class="close" id="closeImportProductModal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('product.import') }}">
                        @csrf
                        <div class="form-group">
                            <label for="uploadName">Choose Excel File</label>
                            <input type="file" id="uploadName" name="product_upload" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                        <button type="button" class="btn btn-secondary" id="cancelImportProductModal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/ProductScript.js') }}"></script>

@endsection
