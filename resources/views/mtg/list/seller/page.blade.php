@extends('mtg/layout')

@section('title', 'Color Creep: MTG')

@section('sidebar')
    @parent
@stop

<style>
    #content {
        height: 75vh;
        overflow: scroll;
    }

    #loading {
        display: none;
    }
</style>

@section('content')
<h2>{{ $data['seller']->seller_str }}</h2>
feedback: (<?php foreach(range(1,5) as $x) {
    if($x <= $data['seller']->feedback_score) { ?>
        <span class="glyphicon glyphicon-star"></span>
    <?php } else {?>
        <span class="glyphicon glyphicon-star-empty"></span>
    <?php } ?>
<?php } ?>)
<div class="row">
    <div class="col-md-2">
        <label>Name</label>
    </div>
    <div class="col-md-2">
        <label>Set</label>
    </div>
    <div class="col-md-1">
        <label>Condition</label>
    </div>
    <div class="col-md-1">
        <label>Foil</label>
    </div>
    <div class="col-md-1">
        <label>Price</label>
    </div>
    <div class="col-md-1">
        <label>Quantity</label>
    </div>
    <div class="col-md-3">
        <label>Add To Cart</label>
    </div>
</div>
    <?php foreach($data['cards'] as $card) { ?>
        @include('mtg/list/seller/item')
        <hr>
    <?php } ?>
    <div id="last" start="50" key="{{{ $data['search']['key'] }}}" value="{{{ $data['search']['value'] }}}"></div>
    <div id="loading"></div>

<script>
$(function() {
    i = 0;
    setInterval(function() {
        i = ++i % 7;
        $("#loading").html("loading"+Array(i+1).join("."));
    }, 500);

    function nextCardAjaxCall(start, key, value) {
        return $.ajax({
            url: "/mtg/next_cards",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                start: start,
                key: key,
                value: value
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

    $('#content').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            return; // currently unavailable for seller page
            var last = $("#last");
            var start = last.attr('start');
            var column = last.attr('key');
            var row = last.attr('value');
            if(typeof start === 'undefined' || typeof column === 'undefined' || typeof row === 'undefined'){
                return;
            };
            // console.debug(start, column, row);
            $("#loading").show();
            $("#loading").fadeOut(2000);

            setTimeout(function() {
                var response = nextCardAjaxCall(start, column, row).responseText;
                // console.log(response);
                last.attr('id', 'none');
                last.append().html(response); } , 900)
        }
    });
});
</script>

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
        console.log(response);
        var check = parseInt(response);

        if(isNaN(check)) {
            qRemain.html(response.substr(0,30));
            return false;
        }
        qRemain.html(check);
        return true;
    }

    $(".to_add").click(function(){
        var id = $(this).attr('id'); // selector id
        var i = id.replace(/\D/g, ''); // inventory_id
        var q = $('.add_quantity#'+id).val(); // quantity added
        var n = $('.name#'+id)[0].innerHTML.trim(); // preview name;
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
