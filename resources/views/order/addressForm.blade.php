@extends('layout')

@section('title', 'Register your address')

@section('sidebar')
    @parent
@stop

@section('content')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>

<form method="post" action="{{ url('address') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <div class="row">
        <label class="col-md-2">Title</label>
        <input type="text" name="title" class="col-md-5"/>
    </div>
    <div class="row">
        <label class="col-md-2">Address Line 1</label>
        <input type="text" name="street1" class="col-md-5" required/>
    </div>
    <div class="row">
        <label class="col-md-2">Address Line 2</label>
        <input type="text" name="street2" class="col-md-5"/>
    </div>
    <div class="row">
        <label class="col-md-2">City</label>
        <input type="text" name="city" class="col-md-5" required/>
    </div>
    <div class="row">
        <label class="col-md-2">State</label>
        <input type="text" name="state" class="col-md-5" required/>
    </div>
    <div class="row">
        <label class="col-md-2">Country</label>
        <input type="text" name="country" class="col-md-5" required/>
    </div>
    <div class="row">
        <label class="col-md-2">Zipcode</label>
        <input type="text" name="zipcode" class="col-md-5" required/>
    </div>
    <div class="row">
        <label class="col-md-2">Phone</label>
        <input type="text" name="phone" class="col-md-5"/>
    </div>
    <div class="row">
        <label>I agree to this user agreement.</label>
        <input type="checkbox" name="agreement" value="1" required>
        <a href="{{ url('user_agreement') }}">User Agreement</a></div>
    <br class="row">
    <div><input type="submit" class="btn" value="Add Address"></div>
</form>
@stop

@section('footer')
    @parent
@stop
