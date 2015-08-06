<div class="col-md-5">{{ $item->name_str }}</div>
<div class="col-md-3">{{ $item->quantity }}</div>
<div class="col-md-3">${{ number_format($item->price/100, 2) }}</div>
