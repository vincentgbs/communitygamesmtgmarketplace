<div class="row item_row" id="inv{{ $card->inventory_id }}">
    <div class="col-md-2">
        <a href="{{ url('mtg/sellers') }}/{{ $card->seller_id }}">{{ $card->seller_str }}</a></div>
    <div class="col-md-2">{{ $card->condition_str }}</div>
    <div class="col-md-1">{{ $card->special_str }}</div>
    <div class="col-md-1 price"
        id="inv{{ $card->inventory_id }}">
            {{ isset($card->price) ? number_format($card->price/100, 2) : '' }}</div>
    <div class="col-md-1 quantity"
        id="inv{{ $card->inventory_id }}">{{ $card->quantity }}</div>
    <div class="col-md-1 hover-display" id="green_shipping">
        <input type="checkbox" <?php echo ($card->green_amt===1)?'checked':null; ?>
            disabled readonly>
    </div>
    <div class="col-md-3">
        <input type="text" name="add_quantity" class="add_quantity"
            id="inv{{ $card->inventory_id }}" size=3/>
        <input type="button" class="btn btn-default to_add"
            id="inv{{ $card->inventory_id }}" value="Add">
        <input type="button" class="btn btn-success disabled item_added"
            id="inv{{ $card->inventory_id }}" value="Item added"/>
    </div>
</div>
