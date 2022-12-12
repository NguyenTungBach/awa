@php
$headerColor = '#0282CB';
$columnColor = '#EBF8FF';
@endphp
<style>
    table {
        border: 1px solid #cdcdcd;
    }
    table tr th {
        border: 1px solid #cdcdcd;
    }
    table tr td {
        border: 1px solid #cdcdcd;
    }
    td {
        text-align: center;
        padding: 3px 8px;
    }
    tr.highlight td {
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

<table id="pdf-header">
    <tr>
        <td colspan="12">株式会社グローバルエアカーゴ</td>
    </tr>
    <tr style="border-bottom: 1px solid #cdcdcd">
        <td style="border-bottom: 1px solid #000; font-size: 12.5px" colspan="12"> <strong>月別 実務実績表</strong> {{ date('Y', $viewDate) }}年{{ date('n', $viewDate) }}月</td>
    </tr>
</table>
<table>
    <thead>
    <tr>
        @foreach($titles as $title)
        <th bgcolor="{{ $headerColor }}" style="vertical-align: bottom; color: #FFFFFF; text-rotate: 90"><strong><div>{{ $title }}</div></strong></th>
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
            <td bgcolor="{{ $columnColor }}" width="75px" style="border: 1px solid #cdcdcd; text-align: center">{{ $item['driver_code'] }}</td>
            <td bgcolor="{{ $columnColor }}" >{{ $flag }}</td>
            <td bgcolor="{{ $columnColor }}" style="border: 1px solid #cdcdcd; text-align: center">{{ $item['driver_name'] }}</td>

            @foreach($item['reports'] as $col => $val)
                @if($col != 'driver_code')
                <td>{{ $val }}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
