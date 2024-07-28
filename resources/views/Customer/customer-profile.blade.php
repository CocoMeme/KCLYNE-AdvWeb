@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ asset('css/customer-profile.css') }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
</head>
<body>
                <div class="col-12">
                    <div class="card user-card-full">
                        <div class="row m-l-0 m-r-0">
                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                <div class="card-block text-center text-white">
                                    <div class="m-b-25">
                                        <img src="/Images/Customers/{{ Auth::user()->customer->image }}" class="img-radius" alt="User-Profile-Image">
                                    </div>
                                    <h6>{{ $customer->name }}</h6>
                                    <a href="#" data-toggle="modal" data-target="#updateProfileModal">
                                        <i class="mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-block">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Email</p>
                                            <h6 class="text-muted f-w-400">{{ $customer->email }}</h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Phone</p>
                                            <h6 class="text-muted f-w-400">{{ $customer->phone }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Address</p>
                                            <h6 class="text-muted f-w-400">{{ $customer->address }}</h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Status</p>
                                            <h6 class="text-muted f-w-400 {{ $customer->status == 'Active' ? 'status-active' : 'status-deactivated' }}">
                                                {{ $customer->status }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        


    <!-- Update Profile Modal -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="messageContainer">
                    <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                    <div id="errorMessage" class="alert alert-error" style="display: none;"></div>
                    <div id="noChangesMessage" class="alert alert-info" style="display: none;">No changes were made.</div>
                </div>
                <form id="updateProfileForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden fields to hold original values -->
                    <input type="hidden" id="originalName" value="{{ $customer->name }}">
                    <input type="hidden" id="originalEmail" value="{{ $customer->email }}">
                    <input type="hidden" id="originalPhone" value="{{ $customer->phone }}">
                    <input type="hidden" id="originalAddress" value="{{ $customer->address }}">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}">
                    </div>
                    <div class="form-group">
                        <label for="image">Profile Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#updateProfileForm').validate({
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            email: {
                required: true,
                email: true,
                maxlength: 255
            },
            phone: {
                required: true,
                maxlength: 20
            },
            address: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            name: {
                required: "Please enter your name",
                maxlength: "Your name must be less than 255 characters"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address",
                maxlength: "Your email must be less than 255 characters"
            },
            phone: {
                required: "Please enter your phone number",
                maxlength: "Your phone number must be less than 20 characters"
            },
            address: {
                required: "Please enter your address",
                maxlength: "Your address must be less than 255 characters"
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);

            // Get original values
            var originalName = $('#originalName').val();
            var originalEmail = $('#originalEmail').val();
            var originalPhone = $('#originalPhone').val();
            var originalAddress = $('#originalAddress').val();

            // Get new values
            var newName = $('#name').val();
            var newEmail = $('#email').val();
            var newPhone = $('#phone').val();
            var newAddress = $('#address').val();

            // Check if there are changes
            if (newName === originalName && newEmail === originalEmail && newPhone === originalPhone && newAddress === originalAddress && !$('#image')[0].files.length) {
                $('#noChangesMessage').show();
                $('#successMessage').hide();
                $('#errorMessage').hide();
                return;
            }

            $.ajax({
                url: '{{ route("profile.update") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#successMessage').text('Profile updated successfully!').show();
                        $('#errorMessage').hide();
                        $('#noChangesMessage').hide();
                        setTimeout(function() {
                            location.reload(); // Reload to show updated profile
                        }, 2000);
                    } else {
                        $('#errorMessage').text('Error updating profile').show();
                        $('#successMessage').hide();
                        $('#noChangesMessage').hide();
                    }
                },
                error: function(xhr) {
                    $('#errorMessage').text('Error updating profile').show();
                    $('#successMessage').hide();
                    $('#noChangesMessage').hide();
                }
            });
        }
    });

    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'File size must be less than {0}');
});
</script>

<style>
    header{
        display: flex;
    }
</style>
</body>
</html>
@endsection