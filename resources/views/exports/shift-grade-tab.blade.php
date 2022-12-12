@php
$headerColor = '#0282CB';
$columnColor = '#EBF8FF';

$carlendars = $data['calendars'];
$year = date('Y', strtotime($data['startDate']));
$month = date('n', strtotime($data['startDate']));
$total = [];
@endphp
<style>
    table#main-report {
        border: 1px solid #cdcdcd;
    }
    table#main-report thead th {
        color: #FFFFFF;
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
<table id="header">
    <tr>
        <td colspan="4">株式会社グローバルエアカーゴ</td>
    </tr>
    <tr style="border-bottom: 1px solid #cdcdcd">
        <td style="border-bottom: 1px solid #000; font-size: 12.5px" colspan="4"> <strong>人件費表</strong> {{ $year }}年{{ $month }}月</td>
    </tr>
</table>
<table id="main-report" cellpadding="3px" autosize="1" width="100%">
    <thead>
        <tr>
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; text-align: center; width: 75px;" colspan="3"></th>
            @foreach($carlendars as $dt => $d)
                <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; text-align: center;" width="75px"><strong>{{ date('j', strtotime($dt)) }}({{ $d['week'] }})</strong></th>
            @endforeach
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; vertical-align: middle; text-align: center; width: 75px;" rowspan="2">合計</th>
        </tr>
        <tr>
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; text-align: center;"><strong>{{ _('Crew番号')}}</strong></th>
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; text-align: center;"><strong>{{ _('Crew区分')}}</strong></th>
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; text-align: center;"><strong>{{ _('Crew名')}}</strong></th>
            @foreach($carlendars as $d)
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; border: 1px solid #cdcdcd; text-align: center;"><strong>{{ $d['rokuyou'] }}</strong></th>
            @endforeach
        </tr>
    </thead>

    <tbody>
    @foreach($items as $item)
        @php
        switch ($item['flag']) {
            case 'lead':
                $flag = '管理職';
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
        @endphp
        <tr>
            <td bgcolor="{{ $columnColor }}" style="border: 1px solid #cdcdcd; text-align: center; width: 75px;">{{ $item['driver_code'] }}</td>
            <td bgcolor="{{ $columnColor }}">{{ $flag }}</td>
            <td bgcolor="{{ $columnColor }}" style="border: 1px solid #cdcdcd; width: 75px;">{{ $item['driver_name'] }}</td>
            @foreach($item['shift_list'] as $jobs)
                @php
                    $date = $jobs['date'];
                    if (!isset($total[$item['driver_code']])) {
                        $total[$item['driver_code']] = [];
                    }
                    $total[$item['driver_code']][date('j', strtotime($date))] = $jobs['value'];
                @endphp
                <td style="border: 1px solid #cdcdcd;">{{ $jobs['value'] }}</td>
            @endforeach
            <td>{{ array_sum($total[$item['driver_code']]) }}</td>
        </tr>
    @endforeach
    <tr>
        <td bgcolor="{{ $columnColor }}" style="text-align: center" colspan="3">合計</td>
        @php
            $fromDate = \Carbon\Carbon::parse($data['startDate'])->day;
            $toDate = \Carbon\Carbon::parse($data['endDate'])->day;
            for($d = $fromDate; $d <= $toDate; $d ++) {
                $total['all'][$d] = $totalDays = array_sum(array_column($total, $d));
                echo "<td>$totalDays</td>";
            }
        @endphp
        <td>{{ array_sum($total['all']) }}</td>
    </tr>
    </tbody>
</table>
