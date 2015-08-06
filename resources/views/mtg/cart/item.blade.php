<div class="row item_row" id="inv{{ $item->inventory_id }}">
    <div class="col-md-2">
        <a href="#">{{ $item->seller_str }}</a>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/cards') }}/{{ $item->card_id }}">{{ $item->name_str }}</a>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/sets') }}/{{ $item->set_id }}">{{ $item->set_str }}</a>
    </div>
    <div class="col-md-1">{{ $item->condition_str }}</div>
    <div class="col-md-1"><input type="checkbox"
        <?php if($item->special_id==2) { echo "checked"; }?> disabled/></div>
    <div class="col-md-1 price"
        id="inv{{ $item->inventory_id }}">
            {{ isset($item->price) ? number_format($item->price/100, 2) : '' }}</div>
    <div class="col-md-1 quantity"
        id="inv{{ $item->inventory_id }}">{{ $item->quantity }}</div>
    <div class="col-md-2">
        <input type="text" name="update_quantity" class="update_quantity"
            id="inv{{ $item->inventory_id }}" size=3/>
        <input type="button" class="btn btn-default to_update"
            id="inv{{ $item->inventory_id }}" value="Update">
        <input type="button" class="btn btn-primary disabled item_updated"
            id="inv{{ $item->inventory_id }}" value="Item Updated"/>
    </div>
</div>
