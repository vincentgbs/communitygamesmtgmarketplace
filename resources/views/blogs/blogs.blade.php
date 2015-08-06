@extends('layout')

@section('title', 'Blogs')

@section('sidebar')
    @parent
@stop

@section('content')
    <h2>Blogs</h2>
    @foreach ($data['blogs'] as $blog)
        <h3>{{ $blog->title_str }}</h3>
        By: {{ $blog->author_str }} on {{ $blog->date }}
        <p>{{ $blog->post_str }}</p>
        <hr>
    @endforeach
@stop

@section('footer')
    @parent
@stop
