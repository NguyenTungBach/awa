@php
$headerColor = '#0282CB';
$columnColor = '#EBF8FF';
@endphp
<style>
    table#main-report {
        border: 1px solid #cdcdcd;
    }
    table#main-report tr th {
        border: 1px solid #cdcdcd;
    }
    table#main-report tr th:nth-child(n+2) > div {
        /*max-width: 20px;*/
    }
    table#main-report tr td {
        border: 1px solid #cdcdcd;
    }
    table#main-report td {
        text-align: center;
        padding: 3px 8px;
    }
    table#main-report tr.highlight td {
        background-color: #a9a9a9;
    }
</style>
@php
$titles = [
    'Crew番号',
    'Crew区分',
    'Crew名',
    '勤務可能日数',
    '勤務日数',
    '休日数',
    '希望休数',
    '拘束時間',
    '実質乗車時間',
    '休憩時間',
    '残業時間',
    'ポイント',
];
@endphp

<table id="main-report" cellpadding="3px" autosize="1" width="100%" style="overflow: wrap">
    <thead>
    <tr>
        @foreach($titles as $k => $title)
        <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF;"><strong><div >{{ $title }}</div></strong></th>
        @endforeach
    </tr>
    </thead>

    <tbody>
    @foreach($items as $item)
        @php
        switch ($item['flag']) {
            case 'lead':
                $flag = '管理職・リーダー';
                break;
            case 'full':
                $flag = '正社員';
                break;
            case 'part':
                $flag = '契約社員';
                break;
            default:
                $flag = '';
        }
        $trStatusColor = $item['highlight'] == 'yes' ? 'highlight' : '';
        @endphp
        <tr class="{{ $trStatusColor }}">
            <td bgcolor="{{ $columnColor }}">{{ $item['driver_code'] }}</td>
            <td bgcolor="{{ $columnColor }}" >{{ $flag }}</td>
            <td bgcolor="{{ $columnColor }}">{{ $item['driver_name'] }}</td>

            @foreach($item['reports'] as $col => $val)
                @if($col != 'driver_code')
                <td width="7.7%">{{ $val }}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
