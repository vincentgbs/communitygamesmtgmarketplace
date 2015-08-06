@extends('layout')

@section('title', 'Shopping Cart')

@section('sidebar')
    @parent
@stop

@section('content')
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
    @include('mtg/cart')
<?php endforeach; ?>
@endif
<hr>
<form method="post" action="{{ url('payment') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <div class="col-md-4">
        <label class="col-md-4">Shipping: </label>
        <select name="speed" class="col-md-8">
            <option value="1">Standard Shipping</option>
            <option value="2" disabled>Express Shipping</option>
            <option value="3" disabled>Two Day</option>
        </select>
        <br class="col-md-12">
        <label class="col-md-4">Insurance: </label>
        <select name="insurance" class="col-md-8">
            <option value="0">No</option>
            <option value="1" disabled>Yes</option>
        </select>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-2">
                <label>Order Totals</label>
            </div>
            <div class="col-md-3">
                Shipping Estimate <input type="text" id="total_shipping"
                    value="${{ number_format($data['subs']['shipping']/100, 2) }}" readonly disabled/>
            </div>
            <div class="col-md-3">
                Price Subtotal <input type="text" id="total_price"
                    value="${{ number_format($data['subs']['price']/100, 2) }}" readonly disabled/>
            </div>
            <div class="col-md-3">
                Total Quantity <input type="text" id="total_quantity"
                    value="{{ $data['subs']['quantity'] }}" readonly disabled/>
            </div>
        </div>
        <br>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <div class="row">
            <div class="col-md-6">
                <label>Shipping Address:</label>
                <?php foreach($data['addresses'] as $address) { ?>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="radio" name="address" value="{{ $address->address_id}}"
                            <?php if(isset($address->active)) { echo "checked"; } ?>>
                        </div>
                        <div class="col-md-10">
                            <div>{{ $address->street1_str }}</div>
                            <div>{{ $address->city_str }}, {{ $address->state_str }}</div>
                            <div>{{ $address->country_str }} {{ $address->zipcode_int }}</div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-3">
                Total <input type="text" id="order_total"
                    value="${{ number_format($data['subs']['total']/100, 2) }}" readonly disabled size="9"/>
                <br>
                <input type="submit" class="btn btn-success" value="Proceed to Payment">
            </div>
        </div>
    </div>
</form>
<style>
    .item_updated {
        display: none;
    }
</style>
<script>
$(function() {

});
</script>
@stop

@section('footer')
    @parent
@stop
