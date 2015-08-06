@extends('layout')

@section('title', 'Contact Us')

@section('sidebar')
    @parent
@stop

@section('content')
    <h2>Contact Us</h2>
    <form method="post" action="{{ url('process_contact') }}" id="contactForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        Name: <input type="text" name="user"/>
        Type: <select name="type">
            <option value="1">Buying/Selling Problem</option>
            <option value="2">Website Error/Bug</option>
            <option value="3">Suggestion/Idea</option>
            <option value="4">Other</option>
            </select>
            <input type="submit" class="btn" id="move-down" value="Submit"/>
    </form>
    Message: <textarea id="move-up" rows="4" cols="100" name="message" form="contactForm"></textarea>
    <br><br><br><br><br>
<style>
#move-down {
    position: relative;
    top: 12rem;
}
#move-up {
    position: relative;
    top: -0rem;
}
</style>
@stop

@section('footer')
    @parent
@stop
