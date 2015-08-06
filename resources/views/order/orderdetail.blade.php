@extends('layout')

@section('title', 'Order Details')

@section('sidebar')
    @parent
@stop

@section('content')

<?php if (isset($data['order']['mtg_card'][0])) {
    $order = $data['order']['mtg_card'][0];
} else if (isset($data['order']['pkmn_card'][0])) {
    $order = $data['order']['pkmn_card'][0];
}?>
<h3>Order Number: {{ $order->order_id }}</h3>
<hr>
<?php foreach ($data['order']['mtg_card'] as $card): ?>
    @include('mtg/order/item')
<?php endforeach; ?>
<hr>
<label>Shipping:</label> ${{ number_format($order->shipping_amt/100, 2) }}
<label>Order Total</label>
@stop

@section('footer')
    @parent
@stop
