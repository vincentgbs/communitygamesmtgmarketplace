@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
    <!-- <p>This is appended to the master sidebar.</p> -->
@stop

<style>
    #content {
        height: 40rem;
        overflow: scroll;
    }
</style>
@section('content')

@stop

@section('footer')
    @parent
    <!-- <p>This is appended to the footer.</p> -->
@stop
