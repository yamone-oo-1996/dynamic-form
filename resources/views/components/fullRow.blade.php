@foreach ($formElements as $formElement)
    @if ($formElement['element_type'] === 'paragraph')
        <div class="full-row">
            <p><strong>{{ $formElement['label'] }}</strong></p>
        </div>
    @else
        @php
            $value = $data[$formElement['attribute_name']] ?? '';
        @endphp
        @if ($optional != '1' || $value != '')
            <div class="full-row">
                <div class="column-6">
                    <strong> {{ $formElement['label'] }} - </strong>
                </div>
                <div class="column-6">
                    {{ $value }}
                </div>
            </div>
        @endif
    @endif
@endforeach
