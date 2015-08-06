@extends('mtg/layout')

@section('title', 'Mtg: Inventory')

@section('sidebar')
    @parent
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <label class="col-md-3">Card: </label>
        <div class="col-md-9">
            <input type="text" id="card" />
            <input type="text" id="card_set" readonly />
            <input type="hidden" id="card_id" readonly />
            <input type="hidden" id="inventory_id" readonly />
        </div>
    </div>
    <div class="col-md-2">
        <label>Price: </label>
        <input type="text" id="price" size="6" placeholder="$0.00"/>
    </div>
    <div class="col-md-2">
        <label>Quantity: </label>
        <input type="text" id="quantity" size="6" placeholder="1"/>
    </div>
    <div class="col-md-2">
        <select id="condition">
            <option value="1">Mint</option>
            <option value="2">Near Mint</option>
            <option value="3">Very Good</option>
            <option value="4">Good</option>
            <option value="5">Playable</option>
            <option value="6">Poor</option>
        </select>
    </div>
    <div class="col-md-1">
        <label>Foil: </label>
        <input type="checkbox" id="special" value="1"/>
    </div>
    <div class="col-md-2">
    <input type="button" class="btn" id="add_inventory" value="Add/Update Inventory"/>
    <input type="button" class="btn btn-success disabled" id="added" value="Updated"/>
    </div>
</div>

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
        <label>Special</label>
    </div>
    <div class="col-md-1">
        <label>Price</label>
    </div>
    <div class="col-md-1">
        <label>Quantity</label>
    </div>
    <div class="col-md-3">
    </div>
</div>

<div id="inventory_list">
<?php foreach ($data['inventory'] as $card) { ?>
    @include('mtg/sell/item')
<?php } ?>
</div>

<style>
#added {
    display: none;
}
</style>

<script>
$(function() {
    $("#price").priceFormat({prefix:"$"});

    $( "#card" ).autocomplete({
    minLength: 1,
    source: function (request, response) {
        $.ajax({
            url: "/mtg/cardids",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                search: request.term
            },
            success: function (data) {
                response(data);
            }
        });
    },
    focus: function( event, ui ) {
        $( "#card" ).val( ui.item.name );
        return false;
    },
    select: function( event, ui ) {
        $( "#card" ).val( ui.item.name );
        $( "#card_set" ).val( ui.item.set );
        $( "#card_id" ).val( ui.item.card_id );
        $( "#inventory_id" ).val('');
        return false;
    }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li>" )
      .append( "<a>" + item.name + "<br>" + item.set + "</a>" )
      .appendTo( ul );
    };



    function addInventoryAjax(price, quantity, card, special, condition) {
        return $.ajax({
            url: "/mtg/update_inventory",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                card: card,
                special: special,
                condition: condition,
                price: price,
                quantity: quantity
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

    function updateInventoryAjax(inventory, price, quantity) {
        return $.ajax({
            url: "/mtg/update_inventory",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                inventory: inventory,
                price: price,
                quantity: quantity
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

    function removeInventoryAjax(inventory) {
        return $.ajax({
            url: "/sell/remove",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                inventory: inventory
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

    $("#add_inventory").click(function(){
        var card = $("#card_id").val();
        var inventory = $("#inventory_id").val();
        var p = $("#price").val().replace(/\D/g, '');
        var q = $("#quantity").val().replace(/\D/g, '');
        var s = $("#special").val();
        var c = $("#condition").val();
        if(card == "" || p == "" || q == "" || c == "") {
            alert('Please fill all required fields');
            return false;
        }

        if(inventory != "") {
            var response = updateInventoryAjax(inventory, p, q).responseText;
        } else {
            var response = addInventoryAjax(p, q, card, s, c).responseText;
        }
        // console.log(response);
        $("#inventory_list").prepend().html(response);
        if (response) {
            $(this).hide();
            $("#added").show();
            setTimeout(function()
                { $("#added").hide();
                $("#add_inventory").show(); }, 1000)
        }
    });

    $(".inventory").click(function(){
        var inventoryid = $(this).attr('inventoryid');
        var cardid = $(this).attr('cardid');
        var name = $(this).attr('name');
        var set = $(this).attr('set');
        var price = $(this).attr('price');
        var quantity = $(this).attr('quantity');
        var special = ($(this).attr('special')==2); // 1 normal, 2 foil
        var condition = $(this).attr('condition');
        $( "#card_id" ).val( cardid );
        $( "#inventory_id" ).val( inventoryid );
        $( "#card" ).val( name );
        $( "#card_set" ).val( set );
        $( "#price" ).val( price );
        $( "#quantity" ).val( quantity );
        $("#special").prop("checked", special);
        $( "#condition" ).val( condition );
    });

    $(".remove_inventory").click(function(){
        if (confirm('Remove this item?')) {
            var inventoryid = $(this).attr('inventoryid');
            var id = $(this).attr('id');
            var response = removeInventoryAjax(inventoryid);
            // console.log(response.responseText);
            var row = $(".item_row#"+id);
            if (response) {
                row.hide();
            }
        }
    });

    $("#condition").change(function(){
        $( "#inventory_id" ).val('');
    });

    $('#special').click(function() {
        $( "#inventory_id" ).val('');
    });
});
</script>
<script>
(function(e){e.fn.priceFormat=function(t){var n={prefix:"US$ ",suffix:"",centsSeparator:".",thousandsSeparator:",",limit:false,centsLimit:2,clearPrefix:false,clearSufix:false,allowNegative:false,insertPlusSign:false,clearOnEmpty:false};var t=e.extend(n,t);return this.each(function(){function m(e){if(n.is("input"))n.val(e);else n.html(e)}function g(){if(n.is("input"))r=n.val();else r=n.html();return r}function y(e){var t="";for(var n=0;n<e.length;n++){char_=e.charAt(n);if(t.length==0&&char_==0)char_=false;if(char_&&char_.match(i)){if(f){if(t.length<f)t=t+char_}else{t=t+char_}}}return t}function b(e){while(e.length<l+1)e="0"+e;return e}function w(t,n){if(!n&&(t===""||t==w("0",true))&&v)return"";var r=b(y(t));var i="";var f=0;if(l==0){u="";c=""}var c=r.substr(r.length-l,l);var h=r.substr(0,r.length-l);r=l==0?h:h+u+c;if(a||e.trim(a)!=""){for(var m=h.length;m>0;m--){char_=h.substr(m-1,1);f++;if(f%3==0)char_=a+char_;i=char_+i}if(i.substr(0,1)==a)i=i.substring(1,i.length);r=l==0?i:i+u+c}if(p&&(h!=0||c!=0)){if(t.indexOf("-")!=-1&&t.indexOf("+")<t.indexOf("-")){r="-"+r}else{if(!d)r=""+r;else r="+"+r}}if(s)r=s+r;if(o)r=r+o;return r}function E(e){var t=e.keyCode?e.keyCode:e.which;var n=String.fromCharCode(t);var i=false;var s=r;var o=w(s+n);if(t>=48&&t<=57||t>=96&&t<=105)i=true;if(t==8)i=true;if(t==9)i=true;if(t==13)i=true;if(t==46)i=true;if(t==37)i=true;if(t==39)i=true;if(p&&(t==189||t==109||t==173))i=true;if(d&&(t==187||t==107||t==61))i=true;if(!i){e.preventDefault();e.stopPropagation();if(s!=o)m(o)}}function S(){var e=g();var t=w(e);if(e!=t)m(t);if(parseFloat(e)==0&&v)m("")}function x(){n.val(s+g())}function T(){n.val(g()+o)}function N(){if(e.trim(s)!=""&&c){var t=g().split(s);m(t[1])}}function C(){if(e.trim(o)!=""&&h){var t=g().split(o);m(t[0])}}var n=e(this);var r="";var i=/[0-9]/;if(n.is("input"))r=n.val();else r=n.html();var s=t.prefix;var o=t.suffix;var u=t.centsSeparator;var a=t.thousandsSeparator;var f=t.limit;var l=t.centsLimit;var c=t.clearPrefix;var h=t.clearSuffix;var p=t.allowNegative;var d=t.insertPlusSign;var v=t.clearOnEmpty;if(d)p=true;n.bind("keydown.price_format",E);n.bind("keyup.price_format",S);n.bind("focusout.price_format",S);if(c){n.bind("focusout.price_format",function(){N()});n.bind("focusin.price_format",function(){x()})}if(h){n.bind("focusout.price_format",function(){C()});n.bind("focusin.price_format",function(){T()})}if(g().length>0){S();N();C()}})};e.fn.unpriceFormat=function(){return e(this).unbind(".price_format")};e.fn.unmask=function(){var t;var n="";if(e(this).is("input"))t=e(this).val();else t=e(this).html();for(var r in t){if(!isNaN(t[r])||t[r]=="-")n+=t[r]}return n}})(jQuery)
</script>
@stop

@section('footer')
<div class="row col-md-offset-2">
    <div class="col-md-5">
        <form method="get">
            Quick Search: <input type="text" name="search"/>
            <input type="button" class="btn" value="Search"/>
        </form>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/sell') }}"><input type="button" class="btn" value="Clear"></a>
    </div>
</div>
    @parent
@stop
