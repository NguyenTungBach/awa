@php
    $styleDefault = "border: 1px solid #cdcdcd; font-size: 10pt";
    $styleHeader = "border-bottom: 1px solid #000; font-size: 11pt";
@endphp

<div>
    <table>
        <thead>
            <tr>
                <td colspan="2">出金情報一覧</td>
                <td style="{{ $styleHeader }}">{{ $title }}</td>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach (config('cash_out_statistical.header_export') as $key => $item)
                    <th>{{ $item }}</th>
                @endforeach
            </tr>
            @foreach ($result as $key => $items)
            <tr>
                <td style="{{ $styleDefault }}">{{ $items['driver_code'] }}</td>
                <td style="{{ $styleDefault }}">{{ $items['driver_name'] }}</td>
                <td style="{{ $styleDefault }}">{{ $items['balance_previous_month'] }}</td>
                <td style="{{ $styleDefault }}">{{ $items['payable_this_month'] }}</td>
                <td style="{{ $styleDefault }}">{{ $items['total_payable'] }}</td>
                <td style="{{ $styleDefault }}">{{ $items['total_cash_out_current'] }}</td>
                <td style="{{ $styleDefault }}">{{ $items['balance_current'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>