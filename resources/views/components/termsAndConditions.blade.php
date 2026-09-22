<div class="center">
    <h3 class="terms-header">{{ $label }}</h3>
</div>
<div style="margin:0px 10px">
    @foreach ($formElements as $formElement)
        <p>{!! $formElement['label'] !!}</p>
    @endforeach
</div>

<!-- Signature Section -->
<div style="border-top:1px solid; margin-top:20px; padding: 10px 20px;">
    <div class="row">
        <div class="checkbox-container">
            <img class="checked-box" src="{{ $data['checked_icon'] }}" alt="logo">
            <label>ဝန်ဆောင်မှုရယူသူအနေဖြင့် စည်းမျဉ်းစည်းကမ်းများကို ကိုယ်တိုင်ဖတ်ရှုပြီး နားလည်သဘောတူပါသည်။</label>
        </div>
    </div>

    <div class="row">
        <div class="column-6">
            <div class="column-3">
                <strong> သုံးစွဲသူ အမည် - </strong>
            </div>
            <div class="column-3">
                {{ $data['full_name'] }}
            </div>

        </div>
        <div class="column-6">
            <div class="column-3">
                <strong> သုံးစွဲသူ လက်မှတ် - </strong>
            </div>
            <div class="column-3">
                {!! $data['digital_sign'] !!}
            </div>
        </div>
    </div>
    <div class="clear"></div> <!-- Clear floats -->
</div>
