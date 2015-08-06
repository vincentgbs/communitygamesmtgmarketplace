<div class="row col-md-12">
    <div class="col-md-2">
        {{ $item->order_id }}
    </div>
    <div class="col-md-2">
        {{ $item->name_str }}
    </div>
    <div class="col-md-2">
        {{ $item->set_str }}
    </div>
    <div class="col-md-1">
        {{ $item->condition_str }}
    </div>
    <div class="col-md-1">
        <input type="checkbox" {{ ($item->special_id==2?'checked':null) }} disabled/>
    </div>
    <div class="col-md-1">
        ${{ number_format($item->price/100, 2) }}
    </div>
    <div class="col-md-1">
        {{ $item->quantity }}
    </div>
    <div class="col-md-1">
        <input type="checkbox" class="shipped" value="1" id="id{{ $item->orderitem_id }}"
            inventory="{{ $item->inventory_id }}" order="{{ $item->order_id }}"
            {{ ($item->shipped==1?'checked':null) }} />
    </div>
    <div class="col-md-1">
        <input type="button" class="btn btn-info updated" id="id{{ $item->orderitem_id }}"
            value="Updated"/>
    </div>
</div>
