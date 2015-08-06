@extends('layout')

@section('title', 'Register as a seller')

@section('sidebar')
    @parent
@stop

@section('content')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>

<h2>Register as a seller</h2>
<form method="post" action="{{ url('sell/registering') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <div class="row">
        <label>Seller/Store Name:</label>
        <input type="text" name="seller" maxlength="32" required/>
    </div>
    <div class="row">
        <label>Payment Method:</label>
        <select name="method">
            <option value="1">Paypal</option>
            <option value="2" disabled>Bank Transfer</option>
        </select>
    </div>
    <div class="row">
        <label>Payment Email:</label>
        <input type="email" name="email" required/>
    </div>
    <div class="row">
        <label>Payment Cycle (commission):</label>
        <select name="cycle">
            <option value="1">Monthly (15%)</option>
            <option value="2" disabled>Bimonthly (%12)</option>
            <option value="3" disabled>Biweekly (%18)</option>
        </select>
    </div>
    <div class="row">
        <input type="submit" class="btn" value="Register"/>
    </div>
</form>
<hr>
@stop

@section('footer')
    @parent
@stop
