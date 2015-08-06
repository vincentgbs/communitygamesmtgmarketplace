@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
@stop

@section('content')
<form method="POST" action="{{ url('order/process_feedback') }}" id="feedbackForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="seller_id" value="{{ $data['seller_id'] }}" readonly/>
    <input type="hidden" name="order_id" value="{{ $data['order']->order_id }}" readonly/>
<div class="row">
    <div class="col-md-6">
        <label>Order Number: {{ $data['order']->order_id }}</label>
        <!-- <label>Seller Name: </label> -->
    </div>

    <div class="col-md-6">
        <label class="rating_label">Feedback Score:</label>
        <div class="rating">
            <span><input type="radio" name="feedback_amt" id="str5" value="5"><label for="str5" class="glyphicon glyphicon-star"></label></span>
            <span><input type="radio" name="feedback_amt" id="str4" value="4"><label for="str4" class="glyphicon glyphicon-star"></label></span>
            <span><input type="radio" name="feedback_amt" id="str3" value="3"><label for="str3" class="glyphicon glyphicon-star"></label></span>
            <span><input type="radio" name="feedback_amt" id="str2" value="2"><label for="str2" class="glyphicon glyphicon-star"></label></span>
            <span><input type="radio" name="feedback_amt" id="str1" value="1"><label for="str1" class="glyphicon glyphicon-star"></label></span>
        </div>
    </div>
</div>
    <div class="row col-md-offset-6">
        <br>
        <small class="col-md-2">No issues</small>
            <input type="radio" class="col-md-1" name="issue" value="0" checked>
        <small class="col-md-2">Problem with order</small>
            <input type="radio" class="col-md-1" name="issue" value="1">
        <br>
    </div>
    <div id="move-down">
        <input type="submit" class="btn" value="Submit"/>
    </div>
</form>
<label id="move-up">Feedback:</label><br>
<textarea id="move-up" rows="5" cols="100" name="feedback_str" form="feedbackForm"></textarea>
<hr>
<style>
.rating {
    float:left;
    width:170px;
}
.rating_label {
    float: left;
}
.rating span { float:right; position:relative; }
.rating span input {
    position:absolute;
    top:0px;
    left:0px;
    opacity:0;
}
.rating span label {
    display:inline-block;
    width:30px;
    height:30px;
    text-align:center;
    color:#FFF;
    background:#ccc;
    font-size:30px;
    margin-right:3px;
    line-height:30px;
    border-radius:50%;
    -webkit-border-radius:50%;
}
.rating span:hover ~ span label,
.rating span:hover label,
.rating span.checked label,
.rating span.checked ~ span label {
    background:#F90;
    color:#FFF;
}

#move-down {
    position: relative;
    top: 14rem;
}
#move-up {
    position: relative;
    top: -4rem;
}
</style>
<script>
$(document).ready(function(){
    $(".rating input:radio").attr("checked", false);
    $('.rating input').click(function () {
        $(".rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });

    $('input:radio').change(
    function(){
        var userRating = this.value;
        // console.log(userRating + "/5 stars");
    });

});
</script>

@stop

@section('footer')
    @parent
@stop
