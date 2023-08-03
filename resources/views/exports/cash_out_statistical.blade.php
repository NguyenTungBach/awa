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
            @php
                unset($items['id'], $items['driver_id'], $items['month_line']);
            @endphp
            <tr>
                @foreach ($items as $k => $item)
                    
                    <td bgcolor="{{ $itemColor }}" class="">{{ $item }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>