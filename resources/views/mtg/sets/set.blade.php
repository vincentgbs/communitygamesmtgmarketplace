<div class="row col-md-offset-1">
    <div class="col-md-4">
        <a href="{{ url('mtg/sets') }}/{{ $set->set_id }}">{{ $set->set_str }}</a>
    </div>
    <div class="col-md-3">
        {{ $set->set_abbreviation }}
    </div>
    <div class="col-md-3">
        {{ $set->date }}
    </div>
</div>
