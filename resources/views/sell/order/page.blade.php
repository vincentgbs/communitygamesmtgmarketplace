@extends('mtg/layout')

@section('title', 'Sales Orders')

@section('sidebar')
    @parent
@stop

@section('content')
<div class="row col-md-12">
    <div class="col-md-2">
        <label>Order Number</label>
    </div>
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
    <div class="col-md-1">
        <label>Shipped</label>
    </div>
</div>
    @foreach ($data['orders']['mtg'] as $item)
        @include('sell/order/item')
    @endforeach

<style>
    .updated {
        display: none;
    }
</style>
<script>
$(document).ready(function() {
    $('.shipped').click(function() {
        var id = $(this).attr('id');
        var inventory = $(this).attr('inventory');
        var order = $(this).attr('order');
        if (!$(this).is(':checked')) {
            var response = ajaxShippedUpdate(inventory, order, 0).responseText;
        } else if ($(this).is(':checked')) {
            var response = ajaxShippedUpdate(inventory, order, 1).responseText;
        } else {
            alert('Shipment Status Error');
        }
        // console.log(response);
        response = JSON.parse(response);
        if (response['status'] == 'updated') {
            $(".updated#"+id).val(response['update']);
            $(".updated#"+id).show();
            setTimeout(function() { $(".updated#"+id).hide(); }, response['time'])
        }
    });

    function ajaxShippedUpdate(inventory, order, shipped) {
        return $.ajax({
            url: "/sell/updateShipment",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("#form_token").val(),
                inventory: inventory,
                order: order,
                shipped: shipped
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
});
</script>
@stop

@section('footer')
    @parent
@stop
