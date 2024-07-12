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
                <a class="btn btn-primary" href="#" role="button" data-toggle="modal" data-target="#createProductModal" id="openCreateProductModal"><i class='bx bxs-add-to-queue' ></i>Add Product</a>

                <form method="POST" enctype="multipart/form-data" action="{{ route('product.import') }}">
                    @csrf
                    
                    <button type="submit" class="btn btn-info btn-primary"><i class='bx bxs-file-import'></i>Import Excel File</button>
                    <input type="file" id="uploadName" name="product_upload" required>
                </form>            

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
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm action-button" data-toggle="dropdown">
                                            ...
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item edit-button" href="{{ route('product.edit', $product->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                            <button class="dropdown-item delete-button" data-id="{{ $product->id }}"><i class="fa-solid fa-trash"></i> Delete</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the selected product?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-primary">Yes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Product Modal -->
        <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">Create Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var openCreateProductModalUrl = "{{ route('product.create') }}";
</script>
<script src="{{ asset('js/ProductScript.js') }}"></script>
<script src="{{ asset('js/ModalScripts.js') }}"></script>

@endsection
