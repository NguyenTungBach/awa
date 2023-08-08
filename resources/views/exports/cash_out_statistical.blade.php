<style type="text/css">
    .custom-row {
        border-bottom: 1px SOLID #000000;
        background-color: #ffffff;
        color: #000000;
        font-size: 20pt;
    }
    .custom-header {
        background: #ff765e;
        color: #ffffff;
        font-size: 11pt;
    }
    .custom-item {
        background: #ffffff;
        color: #000000;
        font-size: 11pt;
    }
</style>

@php
    $headerColor = '#ff765e';
    $itemColor = '#ffffff';
@endphp

<div class="ritz grid-container" dir="ltr">
    <table class="waffle" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td bgcolor="{{ $itemColor }}" class="" colspan="2">出金情報一覧</td>
                <td bgcolor="{{ $itemColor }}" class="" colspan="5">{{ $title }}</td>
            </tr>
            <tr></tr>
            <tr>
                @foreach (config('cash_out_statistical.header_export') as $key => $item)
                    <td bgcolor="{{ $headerColor }}">{{ $item }}</td>
                @endforeach
            </tr>
            @foreach ($result as $key => $items)
            <tr>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['driver_code'] }}</td>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['driver_name'] }}</td>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['balance_previous_month'] }}</td>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['payable_this_month'] }}</td>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['total_payable'] }}</td>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['total_cash_out_current'] }}</td>
                <td bgcolor="{{ $itemColor }}" class="">{{ $items['balance_current'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>