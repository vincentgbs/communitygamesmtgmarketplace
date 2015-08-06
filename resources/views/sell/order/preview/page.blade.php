@extends('mtg/layout')

@section('title', 'Sales Orders')

@section('sidebar')
    @parent
@stop

@section('content')
    @foreach ($data['orders'] as $order)
        <div class="row">
            <label>Order Number:</label> <a href="?id={{ $order->order_id }}">{{ $order->order_id }}</a>
            
            <label>Item Subtotal:</label> ${{ number_format($order->subtotal/100, 2) }}
        </div>
    @endforeach
@stop

@section('footer')
    @parent
@stop
