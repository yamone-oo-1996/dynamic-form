<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
@include('components.styles')

<body>
    <!-- Logo Section -->
    @if (!empty($data['logo']))
        <div class="header">
            <img class="logo"
                src="data:{{ mime_content_type($data['logo']) }};base64,{{ base64_encode(file_get_contents($data['logo'])) }}"
                alt="logo">
        </div>
    @endif
    <div class="container">
        <!-- Header Section -->
        <div class="center">
            <h3>Lan Service Voucher</h3>
        </div>
        <div>
            <div class="left">
                <strong>Voucher ID - {{ $data['voucher_id'] ?? '' }}</strong>
            </div>
            <div class="right">
                နေ့စွဲ - {{ $data['today_date'] }}
            </div>
        </div>

        <!-- Customer Information Section -->

        @php
            $hasToShowHeader = true;
        @endphp
        <!-- Form Sections -->
        <div class="border" style="padding:10px 20px">

            @foreach ($form as $key => $formGroup)
                <div class="center">
                    <h3>{{ $formGroup['label'] }}</h3>
                </div>

                <!-- Tables  -->
                @if (isset($formGroup['form_group_rules']['isTable']) && $formGroup['form_group_rules']['isTable'])
                    @include('components.table', [
                        'formElements' => $formGroup['form_elements'],
                        'data' => $data,
                    ])

                    <!-- Full Row Groups -->
                @elseif (isset($formGroup['form_group_rules']['isFullRow']) && $formGroup['form_group_rules']['isFullRow'] == '1')
                    @include('components.fullRow', [
                        'formElements' => $formGroup['form_elements'],
                        'data' => $data,
                        'optional' => $formGroup['form_group_rules']['isOptional'] ?? '0',
                    ])

                    <!-- Normal Half Row Groups -->
                @else
                    @include('components.halfRow', [
                        'formElements' => $formGroup['form_elements'],
                        'data' => $data,
                        'optional' => $formGroup['form_group_rules']['isOptional'] ?? '0',
                    ])
                @endif

                <!-- Add Sign Group If Needed -->
                @if (isset($formGroup['form_group_rules']['needSign']) && $formGroup['form_group_rules']['needSign'] == '1')
                    <div class="row" style="padding-top: 10px">
                        <div style="border-top:1px solid; padding-top:10px">
                            @include('components.digitalSign', ['data' => $data])
                        </div>
                    </div>
                @endif
                <div class="clear"></div>
            @endforeach

            <!-- Terms and Conditions with Sign Section -->
            <div style="border-top:1px solid; padding-top:10px">
                <div class="row">
                    <div class="column-6">
                        <div class="column-3">
                            <strong> Engineer ID - </strong>
                        </div>
                        <div class="column-3">
                            {{ $data['engineer_id'] ?? '' }}
                        </div>
                    </div>
                    <div class="column-6">
                        <div class="column-3">
                            <strong> Customer Signature - </strong>
                        </div>
                        <div class="column-3">
                            {!! $data['digital_sign'] !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="column-6">
                        <div class="column-3">
                            <strong> Engineer Name - </strong>
                        </div>
                        <div class="column-3">
                            {{ $data['engineer_name'] ?? '' }}
                        </div>
                    </div>
                    <div class="column-6">
                        <div class="column-3">
                            <strong> Customer Name - </strong>
                        </div>
                        <div class="column-3">
                            {{ $data['full_name'] ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
