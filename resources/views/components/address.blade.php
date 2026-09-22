@if (!empty($addressData))
<div class="row">
    <div class="column-6">
        <div class="column-3"><strong> အိမ်နံပါတ် (သို့) တိုက်နံပါတ် - </strong></div>
        <div class="column-3">
            {{ $addressData['building_number'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> အိမ်ရာအမည် - </strong></div>
        <div class="column-3">
            {{ $addressData['house_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> အခန်းနံပါတ်နှင့် အလွှာ - </strong></div>
        <div class="column-3">
            {{ isset($addressData['room_number']) && $addressData['room_number'] ? $addressData['room_number'] . '/' : '' }}
            {{ $addressData['floor_number'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> ယူနစ် - </strong></div>
        <div class="column-3">
            {{ $addressData['unit_address'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> လမ်းအမည် - </strong></div>
        <div class="column-3">
            {{ $addressData['street_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> cross-street - </strong></div>
        <div class="column-3">
            {{ $addressData['cross_street'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> ဘလောက် - </strong></div>
        <div class="column-3">
            {{ $addressData['block_number'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> ရပ်ကွက် - </strong></div>
        <div class="column-3">
            {{ $addressData['areagroup_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> မြို့နယ် - </strong></div>
        <div class="column-3">
            {{ $addressData['township_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> မြို့ - </strong></div>
        <div class="column-3">
            {{ $addressData['city_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3"><strong> တိုင်း / ပြည်နယ် - </strong></div>
        <div class="column-3">
            {{ $addressData['state_name'] ?? '' }}
        </div>
    </div>
</div>
@if (in_array($type, ['full_address', 'comp_full_address_type']))
<div class="row">
    <div class="col-3">
        <strong> မှတ်ချက် - </strong>
    </div>
    <div class="col-9">
        ( {!! $addressData['left_bound_street_name'] ?? '&nbsp;-&nbsp;' !!} )
        နှင့်
        ( {!! $addressData['right_bound_street_name'] ?? '&nbsp;-&nbsp;' !!} )
        လမ်းကြားတွင် တည်ရှိသည်။
    </div>
</div>
@endif
@endif