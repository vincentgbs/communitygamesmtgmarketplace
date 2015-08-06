<li role="presentation">
    <div class="col-md-5">Item</div>
    <div class="col-md-3">Quantity</div>
    <div class="col-md-3">Price</div>
</li>
<?php if(!empty($data['cartPreview'])) {
    foreach ($data['cartPreview'] as $item) { ?>
    <li class="cart">
        @include('buy/itemPreview')
    </li>
    <?php }
    } ?>
