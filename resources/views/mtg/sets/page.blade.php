@extends('mtg/layout')

@section('title', 'Color Creep: MTG')

@section('sidebar')
    @parent
@stop

@section('content')
<div class="row col-md-offset-1">
    <div class="col-md-4">
        <label>Set Name
            <a href="?sort=nameup">&#9650;</a>
            <a href="?sort=namedown">&#9660;</a>
        </label>
    </div>
    <div class="col-md-3">
        <label>Abbreviation</label>
    </div>
    <div class="col-md-3">
        <label>Release Date
            <a href="?sort=dateup">&#9650;</a>
            <a href="?sort=datedown">&#9660;</a>
        </label>
    </div>
</div>
    <?php foreach($data['sets'] as $set) { ?>
        @include('mtg/sets/set')
    <?php } ?>
@stop

@section('footer')
    @parent
@stop
