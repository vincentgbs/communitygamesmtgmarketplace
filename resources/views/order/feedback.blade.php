@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
@stop

@section('content')
<h2>Feedback</h2>
<h3>Order Number: {{ $data['order']->order_id }}</h3>
<div>Seller: {{ $data['order']->seller_str }}</div>
<div>Feedback Score: {{ $data['order']->feedback_amt }}/5</div>
<div>Feedback: "{{ $data['order']->feedback_str }}"</div>
@stop

@section('footer')
    @parent
@stop
