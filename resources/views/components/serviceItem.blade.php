<div class="row">
    <div class="col-12">
        <table class="service-item-table">
            <tbody>
                @if (isset($serviceItems['items']) && is_array($serviceItems['items']))
                    <tr>
                        <th>Service Items</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    @foreach ($serviceItems['items'] as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price']) }}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td>{{ number_format($item['total']) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <p>It’s to confirm that <strong>Bill Collector Team</strong> will collect the above mentioned service charges <strong>{{ isset($serviceItems['grand_total']) ? number_format($serviceItems['grand_total']) : '' }}</strong> and monthly fees at month-end. </p>
    </div>
</div>
