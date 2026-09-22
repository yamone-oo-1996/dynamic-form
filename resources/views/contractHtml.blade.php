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
            <h3>Service Order Form ( {{ $data['ref_id'] ?? '' }} )</h3>
        </div>

        <!-- Customer Information Section -->
        <div class="border" style="padding: 5px 20px;">
            <div class="center">
                <h3>ဝန်ဆောင်မှုမှာယူသူ၏ အချက်အလက်များ</h3>
            </div>
            <div class="right">
                နေ့စွဲ - {{ $data['today_date'] }}
            </div>
        </div>

        @php
            $hasToShowHeader = true;
        @endphp
        <!-- Form Sections -->
        @foreach ($form as $key => $formGroup)
            @if (isset($formGroup['form_group_rules']['nextPage']) && $formGroup['form_group_rules']['nextPage'] == '1')
                @if ($hasToShowHeader)
                    <!-- Footer Section -->
                    @include('components.footer', ['data' => $data])
                    @php
                        $hasToShowHeader = false;
                    @endphp
                @endif
                <div class="page-break"></div>
            @endif

            <!-- Terms And Conditions  -->
            @if (isset($formGroup['form_group_rules']['type']) && $formGroup['form_group_rules']['type'] == 'policy_modal')
                @foreach ($formGroup['form_elements'] as $formElement)
                    @php
                        $label = $formGroup['label'];
                        $formElements = $formGroup['form_elements'];
                    @endphp
                @endforeach
                @continue
            @endif

            <div class="border" style="padding:10px 20px">
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
            </div>
        @endforeach


        <!-- Terms and Conditions with Sign Section -->
        @include('components.termsAndConditions', ['label' => $label, 'formElements' => $formElements])
    </div>
</body>

</html>
