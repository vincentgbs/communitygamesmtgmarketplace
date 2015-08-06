@extends('layout')

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
<div class="row">
    <div class="col-md-2">
        <label>Name</label>
    </div>
    <div class="col-md-2">
        <label>Set</label>
    </div>
    <div class="col-md-2">
        <label>Type</label>
    </div>
    <div class="col-md-2">
        <label>Color</label>
    </div>
    <div class="col-md-2">
        <label>Price (Estimate)</label>
    </div>
</div>
    <div id="card_list"></div>
    <div id="loading"></div>

    <div id="overlay">
        <span class="glyphicon glyphicon-remove col-md-offset-11" id="overlay_exit"
            style="background-color:white"></span>
        <br>
        <div id="card_preview">
        </div>
    </div>
<script>
$(function() {
    i = 0;
    setInterval(function() {
        i = ++i % 7;
        $("#loading").html("loading"+Array(i+1).join("."));
    }, 500);

    // custom page search function
    $("#search").autocomplete({
        minLength: 1,
        source: function(request, response) {
            $.ajax({
                url: "/mtg/next_cards",
                type: "POST",
                data: {
                    _token: $("#form_token").val(),
                    start: 0,
                    key: 'card',
                    value: request.term
                },
                success: function(data) {
                    // console.log(data);
                    $("#card_list").append().html(data);
                }
            });
        }
    });

});
</script>

<style>
    .not_added {
        display: none;
    }

    #overlay {
        background:rgba(0,0,0,0.5);
        width:100%;
        height:100%;
        position:absolute;
        top:0;
        left:0;
        display:none;
    }
    #card_preview {
        background-color: white;
        width: 80%;
        height: 60%;
        margin: 0 auto;
        position: relative;
    }
</style>
<script>
$(function() {
    function buyPreviewAjax(cardid) {
        return $.ajax({
            url: "/mtg/buy_card_preview",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                card: cardid,
                option: 'low_price'
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

    $("body").on('click', '.get_card_preview', function(){
        var top = $(this).offset()['top'];
        var check = $(this).attr('check');
        if (check == 'disabled') {
            return false;
        }
        var id = $(this).attr('id'); // selector id
        var c = $(this).attr('cardid'); // card id
        var response = buyPreviewAjax(c).responseText;
        // console.log(response);

        if(response.substr(0, 4) == 'info') {
            $("#card_preview").prepend().html(response.substr(4));
            $("#overlay").css({top:0}); // reset position
            $("#overlay").offset({left:0, top:top-50});
            $("#overlay").show();
        } else {
            $(this).hide();
            $(".not_added#"+id).show();
            setTimeout(function()
                { $(".not_added#"+id).hide();
                $(".get_card_preview#"+id).show(); }, 1500)
        }
    });

    $("#overlay_exit").on('click', function(){
        $("#overlay").hide();
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
