@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
@stop

@section('content')

<div class="row col-md-6">
    <h2>Recent Posts</h2>
    @foreach ($data['blogs'] as $blog)
        <div class="row col-md-12">
            <div class="col-md-10">
                <form method="post" action="{{ url('blog/post') }}" id="editPost{{ $blog->blog_id }}">
                    <input type="hidden" name="function" value="edit"/>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="blog_id" value="{{ $blog->blog_id }}"/>
                    <div>Date: {{ $blog->date }}</div>
                    <div class="view" id="title{{ $blog->blog_id }}">Title: {{ $blog->title_str }}</div>
                    <input type="text" class="edit" id="title{{ $blog->blog_id }}" name="title" value="{{ $blog->title_str }}" />
                    <div class="view" id="post{{ $blog->blog_id }}">Post: {{ $blog->post_str }}</div>
                    <input type="submit" class="btn" value="Edit"/>
                </form>
                <textarea rows="10" cols="50" class="edit" name="post" form="editPost{{ $blog->blog_id }}"
                    id="post{{ $blog->blog_id }}">{{ $blog->post_str }}</textarea>
            </div>
            <div class="col-md-2">
                <form method="post" action="{{ url('blog/post') }}">
                    <input type="hidden" name="function" value="delete"/>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="blog_id" value="{{ $blog->blog_id }}"/>
                    <input type="button" class="btn delete"
                        id="blog{{ $blog->blog_id }}" value="Delete"/>
                    <input type="submit" class="btn btn-danger confirm_delete"
                        id="blog{{ $blog->blog_id }}" value="Confirm Delete"/>
                </form>
            </div>
        </div>
        <hr>
    @endforeach
</div>
<div class="row col-md-6">
    <h2>New Post</h2>
    <form method="post" action="{{ url('blog/post') }}" id="newPost">
        <input type="hidden" name="function" value="add"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <label>Title:</label> <input type="text" name="title" />
        <input type="submit" class="btn" value="Post"/>
    </form>
    <label>Post:</label><br>
    <textarea rows="10" cols="50" name="post" form="newPost"
        id="newBlog"></textarea>
</div>

<style>
    .confirm_delete {
        display: none;
    }
    .edit {
        display: none;
    }
</style>
<script>
$(document).keyup(function(e) {
    if (e.keyCode == 27) {
        $(".delete").show();
        $(".confirm_delete").hide();
        $(".view").show();
        $(".edit").hide();
    }
});

$(document).ready(function() {
    $(".delete").click(function(){
        var id = $(this).attr('id');
        $(this).hide();
        $(".confirm_delete#"+id).show();
        setTimeout(function()
            {$(".confirm_delete#"+id).hide();
            $(".delete#"+id).show();}, 2500)
    });

    $(".view").dblclick(function(){
        var id = $(this).attr('id');
        $(this).hide();
        $(".edit#"+id).show();
    });
});
</script>


@stop

@section('footer')
    @parent
@stop
