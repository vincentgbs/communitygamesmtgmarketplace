<div class="row item_row" id="inv{{ $card->inventory_id }}">
    <div class="col-md-2">
        <a href="{{ url('mtg/cards') }}/{{ $card->card_id }}">{{ $card->name_str }}</a>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/sets') }}/{{ $card->set_id }}">{{ $card->set_str }}</a>
    </div>
    <div class="col-md-1">{{ $card->condition_str }}</div>
    <div class="col-md-1">{{ $card->special_str }}</div>
    <div class="col-md-1 price"
        id="inv{{ $card->inventory_id }}">
            ${{ isset($card->price) ? number_format($card->price/100, 2) : '0.00' }}</div>
    <div class="col-md-1 quantity"
        id="inv{{ $card->inventory_id }}">{{ number_format($card->total_quantity) }}</div>
    <div class="col-md-3">
        <input type="button" class="btn inventory" value="Update Inventory"
        cardid="{{ $card->card_id }}" inventoryid="{{ $card->inventory_id }}"
        name="{{ $card->name_str }}" set="{{ $card->set_str }}"
        price="{{ number_format($card->price/100, 2) }}" quantity="{{ $card->total_quantity }}"
        condition="{{ $card->condition_id }}" special="{{ $card->special_id }}"/>
    <input type="button" class="btn remove_inventory" value="Remove"
        inventoryid="{{ $card->inventory_id }}" id="inv{{ $card->inventory_id }}"/></div>
</div>
