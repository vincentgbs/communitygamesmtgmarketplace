@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
    <script>
    $(document).ready(function() {
        $("#search").autocomplete({
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: "/mtg/names",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: $("#form_token").val(),
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            }
        });
    });
    </script>
@stop

<style>
    #content {
        height: 40rem;
        overflow: scroll;
    }
</style>
@section('content')
    <h1>Color Creep</h1>
    <br>Home Page<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
    <br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...<br>...
@stop

@section('footer')
    @parent
@stop
