$(document).ready(function () {
    $('#ptable').DataTable({
        ajax: {
            url: "/api/products",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add Product',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#pform").trigger("reset");
                    $('#productModal').modal('show');
                    $('#productUpdate').hide();
                    $('#productImage').remove();
                }
            }
        ],
        columns: [
            { data: 'id' },
            {
                data: null,
                render: function (data, type, row) {
                    return `<img src="${data.image_path}" width="50" height="60">`;
                }
            },
            { data: 'name' },
            { data: 'description' },
            { data: 'price' },
            { data: 'stock_quantity' },
            {
                data: null,
                render: function (data, type, row) {
                    return "<a href='#' class='editBtn' data-id='" + data.id + "'><i class='fas fa-edit' style='font-size:24px'></i></a><a href='#' class='deleteBtn' data-id='" + data.id + "'><i class='fas fa-trash-alt' style='font-size:24px; color:red'></a>";
                }
            }
        ],
    });

    $("#productSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#pform')[0];
        let formData = new FormData(data);

        $.ajax({
            type: "POST",
            url: "/api/products",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $("#productModal").modal("hide");
                var $ptable = $('#ptable').DataTable();
                $ptable.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('#ptable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#productImage').remove();
        $('#productId').remove();
        $("#pform").trigger("reset");

        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'productId', name: 'id', value: id }).appendTo('#pform');
        $('#productModal').modal('show');
        $('#productSubmit').hide();
        $('#productUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/products/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
                $('#stock_quantity').val(data.stock_quantity);
                $("#pform").append(`<img src="${data.image_path}" width="200px" height="200px" id="productImage" />`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#productUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#productId').val();
        var table = $('#ptable').DataTable();
        var data = $('#pform')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/api/products/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $('#productModal').modal("hide");
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('#ptable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var table = $('#ptable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');

        bootbox.confirm({
            message: "Do you want to delete this product?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/products/${id}`,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: "json",
                        success: function (data) {
                            $row.fadeOut(4000, function () {
                                table.row($row).remove().draw();
                            });
                            bootbox.alert(data.success);
                        },
                        error: function (error) {
                            bootbox.alert(error.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
});
