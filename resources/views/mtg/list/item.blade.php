<div class="row">
    <div class="col-md-2">
        <a href="{{ url('mtg/cards') }}/{{ $card->card_id }}">{{ $card->name_str }}</a>
    </div>
    <div class="col-md-2">
        <a href="{{ url('mtg/sets') }}/{{ $card->set_id }}">{{ $card->set_str }}</a>
    </div>
    <div class="col-md-2">{{ $card->type_str }}</div>
    <div class="col-md-2">{{ $card->color_str }}</div>
    <div class="col-md-2">
        <?php if (isset($card->price) && $card->quantity!=0) {
            $disabled = '';
            echo '<a href="/mtg/cards/' . $card->card_id . '">$' . number_format($card->price/100, 2) . '</a>';
         } else {
            $disabled = 'disabled';
            echo "$" . number_format($card->price_est/100, 2);
        } ?>
    </div>
    <div class="col-md-2">
        <input type="button" class="btn get_card_preview {{ $disabled }}" check="{{ $disabled }}"
            id="card{{ $card->card_id }}" cardid="{{ $card->card_id }}" value="Add to Cart"/>
        <input type="button" class="btn btn-danger disabled not_added"
            id="card{{ $card->card_id }}" value="Error"/>
    </div>
</div>
