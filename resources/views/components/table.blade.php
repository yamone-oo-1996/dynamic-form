<table>
    <tr>
        @foreach ($formElements as $formElement)
        <th>{{ $formElement['label'] }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach ($formElements as $formElement)
        <td>{{ $data[$formElement['attribute_name']] ?? '' }}</td>
        @endforeach
    </tr>
</table>