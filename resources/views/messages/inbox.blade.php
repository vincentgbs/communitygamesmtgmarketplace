@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
    <!-- <p>This is appended to the master sidebar.</p> -->
@stop

@section('content')
<div class="row">
    <div class="row col-md-6">
        <h3>Sent:</h3>
        @foreach ($data['from'] as $message)
            <div style="border: thin solid black">
                <div class="col-md-6"><label>Date:</label> {{ $message->date }}</div>
                <div class="col-md-6"><label>To:</label> {{ $message->name }}</div>
                <div><label>Message:</label> {{ $message->message_str }}</div>
            </div>
        @endforeach
    </div>
    <div class="row col-md-6">
        <h3>Received:</h3>
        @foreach ($data['to'] as $message)
            <div style="border: thin solid black">
                <div class="col-md-6"><label>Date:</label> {{ $message->date }}</div>
                <div class="col-md-6"><label>To:</label> {{ $message->name }}</div>
                <div><label>Message:</label> {{ $message->message_str }}</div>
            </div>
        @endforeach
    </div>
</div>
<hr>
    <div class="row col-md-offset-1 col-md-11">
        <h3>Send new message</h3>
        <form method="post" action="{{ url('mail/send') }}" id="message">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            To: <input type="text" id="to"/>
            <input type="hidden" id="to_id" name="to" readonly/><br>
            <input type="submit" class="btn" id="move-down" value="send"/>
        </form>
        <textarea id="move-up" rows="4" cols="100" form="message" name="message"></textarea>
        <br><br><br>
    </div>
<style>
#move-down {
    position: relative;
    top: 10rem;
}
#move-up {
    position: relative;
    top: -2rem;
}
</style>
<script>
$(function() {
    var names = $.ajax({
                    url: "/mail/users",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: $("#form_token").val(),
                    },
                    async: false,
                    success: function (data) {
                        return data;
                    }
                });
    names = JSON.parse(names.responseText);
    // console.log(names);

    $( "#to" ).autocomplete({
    minLength: 1,
    source: names,
    focus: function( event, ui ) {
        $( "#to" ).val( ui.item.name );
        return false;
    },
    select: function( event, ui ) {
        $( "#to" ).val( ui.item.name );
        $( "#to_id" ).val( ui.item.id );
        return false;
    }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li>" )
      .append( "<a>" + item.name + "</a>" )
      .appendTo( ul );
    };

});
</script>
@stop

@section('footer')
    @parent
    <!-- <p>This is appended to the footer.</p> -->
@stop
