<div class="row itemlist">
    @foreach($accessories as $accessory)
        <?php
            $checked = false;
            if($device->accessories->contains($accessory->id)) {
                $checked = true;
            }
        ?>
        <div class="col-md-2 item" data-item-id="{{ $accessory->id }}">
            <div style="height: 200px; text-align: center;">
                @if($accessory->medium)
                    <img src="{{ $accessory->medium->getUrl() }}" style="max-height: 200px;max-width: 100%;" />
                @endif
            </div>
            <label>
                <h4>{{ Form::checkbox(null, null, $checked, ['class' => 'accessory_connect']) }} {{ $accessory->display_title }}</h4>
            </label>
        </div>
    @endforeach
</div>
<div class="row">
    <div class="col-md-12 text-center accessories_links">
        {{ $accessories->links() }}
    </div>
</div>