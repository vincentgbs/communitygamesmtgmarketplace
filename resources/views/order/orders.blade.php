@extends('layout')

@section('title', 'Past Orders')

@section('sidebar')
    @parent
@stop

@section('content')
<div class="row col-md-offset-1">
    <div class="col-md-1">
        <label>Date</label>
    </div>
    <div class="col-md-2">
        <label>Order Id</label>
    </div>
    <div class="col-md-2">
        <label>Order Total</label>
    </div>
    <div class="col-md-3">
        <label>Buyer Email</label>
    </div>
    <div class="col-md-3">
        <label>Address</label>
    </div>
</div>
<?php foreach($data['orders'] as $order) { ?>
    <div class="row col-md-offset-1">
        <div class="col-md-1">
            {{ substr($order->date, 0, 10) }}
        </div>
        <div class="col-md-2">
            <a href="{{ url('order') }}?id={{ $order->order_id }}">{{ $order->order_id }}</a>
        </div>
        <div class="col-md-2">
            ${{ number_format($order->total/100, 2) }} {{ $order->currency }}
        </div>
        <div class="col-md-3">
            {{ $order->email }}
        </div>
        <div class="col-md-3">
            {{ $order->line1 }}<br>
            {{ $order->city }}, {{ $order->state }}
        </div>
    </div>
    <hr>
<?php } ?>

@stop

@section('footer')
    @parent
@stop
