<div class="row item_row" id="inv{{ $card->inventory_id }}">
    <div class="col-md-2">
        <a href="#">{{ $card->seller_str }}</a>
        <a href="{{ url('order/feedback') }}?oid={{ $card->order_id }}&sid={{ $card->seller_id }}">
            <input type="button" class="btn" value="Feedback"></a>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/buy') }}/{{ $card->card_id }}">{{ $card->name_str }}</a>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/sets') }}/{{ $card->set_id }}">{{ $card->set_str }}</a>
    </div>
    <div class="col-md-1">{{ $card->condition_str }}</div>
    <div class="col-md-1">{{ $card->special_str }}</div>
    <div class="col-md-1 price"
        id="inv{{ $card->inventory_id }}">
            ${{ isset($card->price) ? number_format($card->price/100, 2) : '' }}</div>
    <div class="col-md-1 quantity"
        id="inv{{ $card->inventory_id }}">{{ $card->quantity }}</div>
    <div class="col-md-1">
        <input type="checkbox" />
    </div>
</div>
