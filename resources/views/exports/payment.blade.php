@php
    $styleDefault = "border: 1px solid #cdcdcd; font-size: 10pt";
    $styleHeader = "border-bottom: 1px solid #000; font-size: 11pt";
    $styleColumn = "border: 1px solid #cdcdcd; font-size: 11pt; background-color: #ffddc8;";
    $styleRow = "text-align: center; vertical-align: center; font-weight: bold; color: #ffffff; border: 1px solid #cdcdcd; font-size: 11pt; background-color: #ff765e;";
    $styleColumnLast = "text-align: center; vertical-align: center; font-weight: bold; color: #ffffff; border: 1px solid #cdcdcd; font-size: 11pt; background-color: #c0504d;";
@endphp
<div>
    <table>
        <thead>
            <tr>
                <th colspan="2" style="width: 90px">支払代金表</th>
                <th style="{{ $styleHeader }}">{{ $title }}</th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="{{ $styleRow }}" colspan="4"></th>
                {{-- foreach calendar --}}
                @foreach ($calendar as $item)
                    <th style="{{ $styleRow }}">{{ date('d', strtotime($item['date'])).'('.$item['week'].')' }}</th>
                @endforeach
                {{-- 月額合計 --}}
                <th style="{{ $styleColumnLast }} width: 120px" rowspan="2">月額合計</th>
            </tr>
            <tr>
                <th style="{{ $styleRow }}">協力会社ID</th>
                <th style="{{ $styleRow }} width: 60px">締日</th>
                <th style="{{ $styleRow }}">協力会社名</th>
                <th style="{{ $styleRow }}">車両番号</th>
                {{-- foreach calendar --}}
                @foreach ($calendar as $item)
                    <th style="{{ $styleRow }} width: 60px">{{ $item['rokuyou'] }}</th>
                @endforeach
            </tr>
            @foreach ($result['list_data'] as $key => $value)
                <tr>
                    <td style="{{ $styleColumn }}">{{ $value['driver_code'] }}</td>
                    <td style="{{ $styleColumn }}">{{ $value['closing_date'] }}</td>
                    <td style="{{ $styleColumn }}">{{ $value['driver_name'] }}</td>
                    <td style="{{ $styleColumn }}">{{ $value['vehicle_number'] }}</td>
                    @foreach ($calendar as $item)
                        @if (!empty($value['total_payable_day']) && array_key_exists($item['date'], $value['total_payable_day']))
                            <td style="{{ $styleDefault }}">{{ $value['total_payable_day'][$item['date']] }}</td>
                        @else
                            <td style="{{ $styleDefault }}">0</td>
                        @endif
                    @endforeach
                    <td style="{{ $styleDefault }}">{{ $value['payable_this_month'] }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4" style="{{ $styleColumn }} text-align: right; vertical-align: right; font-weight: bold">日別合計</td>
                @foreach ($calendar as $item)
                    @if (array_key_exists($item['date'], $result['sum_total_day']))
                        <td style="{{ $styleDefault }}">{{ $result['sum_total_day'][$item['date']] }}</td>
                    @else
                        <td style="{{ $styleDefault }}">0</td>
                    @endif
                @endforeach
                <td style="{{ $styleDefault }}">{{ $result['sum_total_month'] }}</td>
            </tr>
        </tbody>
    </table>
</div>