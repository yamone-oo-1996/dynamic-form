<div class="full-row">
    <div class="column-6">
        <div class="column-3">
            <strong>တပ်ဆင်သူ အမည် - </strong>
        </div>
        <div class="column-3">
            {{ $data['engineer_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3">
            <strong>သုံးစွဲသူ အမည် - </strong>
        </div>
        <div class="column-3">
            {{ $data['full_name'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3">
            <strong>တပ်ဆင်သူ ID - </strong>
        </div>
        <div class="column-3">
            {{ $data['engineer_id'] ?? '' }}
        </div>
    </div>
    <div class="column-6">
        <div class="column-3">
            <strong>သုံးစွဲသူ လက်မှတ် - </strong>
        </div>
        <div class="column-3">
            {!! $data['digital_sign'] !!}
        </div>
    </div>
</div>
