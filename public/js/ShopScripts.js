var itemCount = 0;
var priceTotal = 0;

$(document).ready(function () { 
    $.ajax({
        type: "GET",
        url: "/api/products",
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $.each(data, function (key, value) {
                var item = `
                    <div class='row'>
                        <div class='itemDetails'>
                            <div class='itemImage'>
                                <img src="/images/Products/${value.image_path.split(',')[0]}"/>
                            </div>
                            <div class='itemText'>
                                <h3>${value.name}</h3>
                                <div class='product-info'>
                                    <p class='price-container'>Price: ₱ <span class='price'>${value.price}</span></p>
                                    <p class='stock-container'>Stock: <span class='price'>${value.stock_quantity}</span></p>
                                    <p>${value.description}</p>
                                </div>    
                            </div>

                            <div class='methods'>
                                <input type='number' class='qty' name='quantity' min='1' max='${value.stock_quantity}' value='1'>
                                <p class='itemId' hidden>${value.id}</p>
                                <button type='button' class='btn btn-primary add'>Add to Cart</button>
                                <button type='button' class='btn btn-primary add'>Buy</button>
                            </div>
                        </div>
                    </div>`;
                $("#items").append(item);
            });
        },
        error: function () {
            console.log('AJAX load did not work');
            alert("error");
        }
    });

    $("#items").on('click', '.add', function () {
        itemCount++;
        $('#itemCount').text(itemCount).css('display', 'block');
        var clone = $(this).closest('.item').clone();
        clone.append('<button class="removeItem btn btn-danger">Remove Item</button>');
        $('#cartItems').append(clone);

        var price = parseFloat($(this).siblings().find('.price').text());
        priceTotal += price;
        $('#cartTotal').text("Total: ₱ " + priceTotal.toFixed(2));
    });

    $('#cartItems').on('click', '.removeItem', function () {
        itemCount--;
        $('#itemCount').text(itemCount);
        if (itemCount === 0) {
            $('#itemCount').css('display', 'none');
        }

        var price = parseFloat($(this).siblings().find('.price').text());
        priceTotal -= price;
        $('#cartTotal').text("Total: ₱ " + priceTotal.toFixed(2));

        $(this).closest('.item').remove();
    });

    $('#emptyCart').click(function () {
        itemCount = 0;
        priceTotal = 0;
        $('#itemCount').text(itemCount).css('display', 'none');
        $('#cartTotal').text("Total: ₱ 0.00");
        $('#cartItems').empty();
    });

    $('#close').click(function () {
        $('#shoppingCart').hide();
    });

    $('.openCloseCart').click(function () {
        $('#shoppingCart').toggle();
    });
});
