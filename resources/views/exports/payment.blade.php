@php

@endphp
<div>
    <table>
        <tbody>
            <tr>
                <td colspan="2">支払代金表</td>
                <td colspan="8">{{ $title }}</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="3"></td>
                {{-- foreach calendar --}}
                @foreach ($calendar as $item)
                    <td>{{ date('d', strtotime($item['date'])).'('.$item['week'].')' }}</td>
                @endforeach
                {{-- 月額合計 --}}
                <td rowspan="2">月額合計</td>
            </tr>
            <tr>
                <td>協力会社ID</td>
                <td>締日</td>
                <td>協力会社名</td>
                {{-- foreach calendar --}}
                @foreach ($calendar as $item)
                    <td>{{ $item['rokuyou'] }}</td>
                @endforeach
            </tr>
            @foreach ($result['list_data'] as $key => $value)
                <tr>
                    <td>{{ $value['driver_code'] }}</td>
                    <td>{{ $value['closing_date'] }}</td>
                    <td>{{ $value['driver_name'] }}</td>
                    @foreach ($calendar as $item)
                        @if (!empty($value['total_payable_day']) && array_key_exists($item['date'], $value['total_payable_day']))
                            <td>{{ $value['total_payable_day'][$item['date']] }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <td>{{ $value['payable_this_month'] }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="3">日別合計</td>
                @foreach ($calendar as $item)
                    @if (array_key_exists($item['date'], $result['sum_total_day']))
                        <td>{{ $result['sum_total_day'][$item['date']] }}</td>
                    @else
                        <td>0</td>
                    @endif
                @endforeach
                <td>{{ $result['sum_total_month'] }}</td>
            </tr>
        </tbody>
    </table>
</div>