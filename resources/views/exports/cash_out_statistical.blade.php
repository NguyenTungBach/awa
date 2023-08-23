@php
    $styleDefault = "border: 1px solid #cdcdcd; font-size: 10pt";
    $styleHeader = "border-bottom: 1px solid #000; font-size: 11pt";
@endphp

<div>
    <table>
        <thead>
            <tr>
                <td colspan="2" style="width: 80px">出金情報一覧</td>
                <td style="{{ $styleHeader }}">{{ $title }}</td>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>協力会社ID</th>
                <th>協力会社名</th>
                <th>前月末残高</th>
                <th style="width: 120px">当月買掛金</th>
                <th style="width: 120px">買掛金合計</th>
                <th style="width: 120px">当月出金金額</th>
                <th style="width: 120px">当月残高</th>
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