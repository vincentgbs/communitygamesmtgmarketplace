@extends('layout')

@section('title', 'Shopping Cart')

@section('sidebar')
    @parent
@stop

@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(!empty($data['cart']['mtg_card']))
<h3>Magic Cards</h3>
<div class="row">
    <div class="col-md-2"><label>Seller</label></div>
    <div class="col-md-2"><label>Name</label></div>
    <div class="col-md-2"><label>Set</label></div>
    <div class="col-md-1"><label>Condition</label></div>
    <div class="col-md-1"><label>Foil</label></div>
    <div class="col-md-1"><label>Price</label></div>
    <div class="col-md-1"><label>Quantity</label></div>
    <div class="col-md-2"><label>Update Cart</label></div>
</div>
<?php foreach ($data['cart']['mtg_card'] as $item): ?>
    @include('mtg/cart/item')
<?php endforeach; ?>
@endif
<hr>
<div class="row">
    <div class="col-md-1 col-md-offset-4">
        <label>Order Totals</label>
    </div>
    <div class="col-md-2">
        Shipping Estimate <input type="text" id="total_shipping"
            value="${{ number_format($data['subs']['shipping']/100, 2) }}" readonly/>
    </div>
    <div class="col-md-2">
        Price Subtotal <input type="text" id="total_price"
            value="${{ number_format($data['subs']['price']/100, 2) }}" readonly/>
    </div>
    <div class="col-md-2">
        Total Quantity <input type="text" id="total_quantity"
            value="{{ $data['subs']['quantity'] }}" readonly/>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-2 col-md-offset-7">
        Order Subtotal <input type="text" id="order_total"
            value="${{ number_format($data['subs']['total']/100, 2) }}" readonly disabled/>
    </div>
    <div class="col-md-2">
        <a href="{{ url('checkout') }}">
            <input type="button" class="btn btn-success" value="Checkout"
            <?php if($data['subs']['total'] == 0) { echo "disabled"; } ?> />
        </a>
    </div>
</div>
<br>
<style>
    .item_updated {
        display: none;
    }
</style>
<script>
$(function() {
    function format(n) {
        var sep = ".";
        var decimals = 2;
        return n.toLocaleString().split(sep)[0]
            + sep + n.toFixed(decimals).split(sep)[1];
    }

    function updateCartAjaxCall(inventoryId, quantity, price) {
        return $.ajax({
            url: "/updateCart",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                q: quantity,
                p: price,
                i: inventoryId
            },
            async: false,
            statusCode: {
                401: function() { alert("Authentication Error"); },
                500: function() { alert("Server Error, please try again later."); }
            },
            success: function(data) {
                return data;
            }
        });
    }

    function updateCart(inventoryId, price, quantity) {
        var d = /\d+/;
        var qRemain = $(".quantity#inv"+inventoryId);
        var price = $(".price#inv"+inventoryId)[0].innerHTML.trim();
        quantity = Math.floor(quantity); // convert number to integer
        price = price.replace(/\D/g,''); // convert dollars to cents
        if(!d.test(quantity) || quantity < 0) {
            alert('Please enter a valid quantity');
            return false;
        }
        if (quantity == 0) {
            var remove = confirm('Remove from cart?');
            if (!remove) {
                return false;
            }
        }
        var response = updateCartAjaxCall(inventoryId, quantity, price).responseText;
        console.log(response);
        var check = parseInt(response);

        if(isNaN(check)) {
            qRemain.html(response.substr(0,20));
            return false;
        }
        qRemain.html(check);
        return true;
    }

    function updateSubAjax() {
        return $.ajax({
            url: "/cart_subtotal",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
            },
            async: false,
            statusCode: {
                401: function () { alert("Authentication Error"); },
                500: function () { alert("Server Error, please try again later."); }
            },
            success: function (data) {
                return data;
            }
        });
    }

    function updateSubtotal() {
        var subship = $("#total_shipping");
        var subprice = $("#total_price");
        var subquantity = $("#total_quantity");
        var total = $("#order_total");

        var r = updateSubAjax().responseText;
        // console.log(r);
        r = r.split('|');
        var priceT = Math.floor(r[0]);
        var quantityT = Math.floor(r[1]);
        var shippingT = Math.floor(r[2]);

        subprice.val("$" + format(priceT / 100));
        subquantity.val(quantityT);
        subship.val("$"+format(shippingT / 100));
        total.val("$"+format((priceT+shippingT) / 100));
    }

    $(".to_update").on('click', function(){
        var id = $(this).attr('id'); // selector id
        var i = id.replace(/\D/g, ''); // inventory_id
        var q = $('.update_quantity#'+id).val(); // quantity updated
        var price = $('.price#'+id); // price div
        var quantity = $('.quantity#'+id); // quantity_remaining div
        var p = price.text().replace(/\D/g, ''); // price in cents
        var d = /\d+/;
        if(updateCart(i, p, q)) {
            updateSubtotal();
            $(this).hide();
            $(".item_updated#"+id).show();
            setTimeout(function()
                { $(".item_updated#"+id).hide();
                $(".to_update#"+id).show(); }, 1000)
        }
    });
});
</script>
@stop

@section('footer')
    @parent
@stop
