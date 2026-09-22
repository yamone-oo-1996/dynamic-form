<div class="row">
    @foreach ($formElements as $formElement)
        @if ($formElement['attribute_name'] == 'choose_business')
            @continue
        @endif
        @if ($formElement['element_type'] == 'paragraph')
            <div class="row">
                <strong>{{ $formElement['label'] }}</strong>
            </div>
        @elseif (in_array($formElement['element_type'], ['address', 'full_address']))
            @include('components.address', [
                'addressData' => $data[$formElement['attribute_name']] ?? '',
                'type' => $formElement['element_type'],
            ])
        @elseif (in_array($formElement['element_type'], ['comp_address_type', 'comp_full_address_type']))
            @if (isset($data[$formElement['attribute_name']]))
                <div class="row">
                    <div class="checkbox-container" style="padding-top:10px;">
                        @php
                            $checked = $data[$formElement['attribute_name']]['check_status']
                                ? $data['checked_icon']
                                : $data['unchecked_icon'];
                        @endphp
                        <img class="checked-box" src="{{ $checked }}" alt="logo">
                        <label>ဝန်ဆောင်မှုတပ်ဆင်ရန်လိပ်စာနှင့်တူပါသည်။</label>
                    </div>
                </div>
                @include('components.address', [
                    'addressData' => $data[$formElement['attribute_name']]['address_info'] ?? '',
                    'type' => $formElement['element_type'],
                ])
            @endif
        @elseif ($formElement['element_type'] == 'service_item')
            @include('components.serviceItem', [
                'serviceItems' => $data[$formElement['attribute_name']] ?? [],
            ])
        @else
            @php
                $value = $data[$formElement['attribute_name']] ?? '';
                if ($formElement['attribute_name'] == 'use_type') {
                    $business = $data['choose_business'] ?? '' ? ' ( ' . $data['choose_business'] . ' ) ' : '';
                    $value = $value . $business;
                }
            @endphp
            @if ($optional != '1' || $value != '')
                <div class="column-6">
                    <div class="column-3">
                        <strong>{{ $formElement['label'] }} - </strong>
                    </div>
                    <div class="column-3">
                        {{ $value }}
                    </div>
                </div>
            @endif
        @endif
    @endforeach
</div>
