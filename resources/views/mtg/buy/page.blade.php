@extends('mtg/layout')

@section('title', 'Color Creep: MTG')

@section('sidebar')
    @parent
@stop

@section('content')

<?php $data['card']; ?>
<div class="row col-md-12">
    <div class="col-md-6 text-left">
        <div><label>Name:</label>
            <text class="name" id="name">{{ $data['card']->name_str }}</text></div>
        <div><label>Set:</label> {{ $data['card']->set_str }}</div>
        <div><label>Type, Subtype:</label> {{ $data['card']->type_str }}; {{ $data['card']->subtype_str }}</div>
        <div><label>Mana Cost:</label>({{ $data['card']->cmc_amt }}); {{ $data['card']->mana_str }}</div>
        <div><label>Text:</label> {{ $data['card']->text_str }}</div>
        <div><label>Flavor Text:</label> {{ $data['card']->flavor_str }}</div>
        <div><label>Artist:</label> {{ $data['card']->artist_str }}</div>
    </div>
    <div class="col-md-6">
        <img src='data:image/jpeg;base64, {{ $data['card']->img_src }}'
        height='210rem' width='150rem'>
    </div>
</div>

<div class="row">
    <div class="col-md-2"><label>Seller</label></div>
    <div class="col-md-2"><label>Condition</label></div>
    <div class="col-md-1"><label>Foil</label></div>
    <div class="col-md-1"><label>Price</label></div>
    <div class="col-md-1"><label>Quantity</label></div>
    <div class="col-md-1 hover-display" id="green_shipping">
        <label>Green</label>
        <style>
        .hover-alert#green_shipping {
            background-color: #FAFAFA;
            left: -150%;
            width: 200%;
        }
        </style>
        <div class="hover-alert" id="green_shipping">
            Items that are tagged 'Green' are eligible for free shipping.
        </div>
    </div>
    <div class="col-md-3"><label>Add to cart</label></div>
</div>
<?php foreach ($data['cards'] as $card) {
        if(isset($card->price)) {?>
    @include('mtg/buy/item')
<?php   }
    } ?>

<style>
    .item_added {
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

    function addToCartAjaxCall(inventoryId, quantity, price, name) {
        return $.ajax({
            url: "/addToCart",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                q: quantity,
                p: price,
                n: name,
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

    function addToCart(inventoryId, price, quantity, name) {
        var d = /\d+/;
        var qRemain = $(".quantity#inv"+inventoryId);
        var qR = qRemain[0].innerHTML.trim();
        var price = $(".price#inv"+inventoryId)[0].innerHTML.trim();
        quantity = Math.floor(quantity); // convert number to integer
        qR = Math.floor(qR); // convert number to integer
        price = price.replace(/\D/g,''); // convert dollars to cents
        if(!d.test(quantity) || quantity > qR || quantity <= 0) {
            alert('Please enter a valid quantity');
            return false;
        }

        var response = addToCartAjaxCall(inventoryId, quantity, price, name).responseText;
        // console.log(response);
        var check = parseInt(response);

        if(isNaN(check)) {
            qRemain.html(response.substr(0,30));
            return false;
        }
        qRemain.html(check);
        return true;
    }

    $("body").on('click', '.to_add', function(){
        var id = $(this).attr('id'); // selector id
        var i = id.replace(/\D/g, ''); // inventory_id
        var q = $('.add_quantity#'+id).val(); // quantity added
        var n = $('.name#name')[0].innerHTML.trim();
        var price = $('.price#'+id); // price div
        var quantity = $('.quantity#'+id); // quantity_remaining div
        var p = price.text().replace(/\D/g, ''); // price in cents
        var d = /\d+/;
        if(addToCart(i, p, q, n)) {
            $(this).hide();
            $(".item_added#"+id).show();
            setTimeout(function()
                { $(".item_added#"+id).hide();
                $(".to_add#"+id).show(); }, 1500)
        }
    });
});
</script>
@stop

@section('footer')
    @parent
@stop
