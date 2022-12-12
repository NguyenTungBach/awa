@php
$headerColor = '#0282CB';
$columnColor = '#EBF8FF';
@endphp
<style>
    table {
        border: 1px solid #cdcdcd;
    }
    table#main-report tr th {
        border: 1px solid #cdcdcd;
        background-color: #0282CB;
        vertical-align: bottom;
        color: #FFFFFF;

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
<table >
    <tr>
        <td colspan="10">株式会社グローバルエアカーゴ</td>
    </tr>
    <tr style="border-bottom: 1px solid #cdcdcd">
        <td style="border-bottom: 1px solid #000; width: 18%; font-size: 20px" colspan="2">シフト表</td>
        <td style="border-bottom: 1px solid #000"  colspan="8">{{ $startDate->format("Y")  . '年' . $startDate->format("n")  . '月' . $startDate->format("j")  . '日(' . $lunarJps[$startDate->toDateString()]  . ')〜' . $endDate->format("Y")  . '年' . $endDate->format("n")  . '月' . $endDate->format("j")  . '日(' . $lunarJps[$endDate->toDateString()]  . ')' }}</td>
    </tr>
</table>
<table id="main-report" cellpadding="3px" autosize="1" width="100%">
    <thead>
        <tr>
            <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF;" colspan="3"></th>
            @foreach($lunarJps as $dt => $d)
                <th bgcolor="{{ $headerColor }}" style="border: 1px solid #cdcdcd; color: #FFFFFF; text-align: center" ><strong>{{ date('j', strtotime($dt)) }}({{ $lunarJps[$dt] }})</strong></th>
            @endforeach
        </tr>
        <tr>
            <th bgcolor="{{ $headerColor }}" style="border: 1px solid #cdcdcd; color: #FFFFFF; text-align: center" ><strong>{{ _('Crew番号')}}</strong></th>
            <th bgcolor="{{ $headerColor }}" style="border: 1px solid #cdcdcd; color: #FFFFFF; text-align: center" ><strong>{{ _('Crew区分')}}</strong></th>
            <th bgcolor="{{ $headerColor }}" style="border: 1px solid #cdcdcd; color: #FFFFFF; text-align: center" ><strong>{{ _('Crew名')}}</strong></th>
            @foreach($lunarJpDayInWeek as $d)
            <th bgcolor="{{ $headerColor }}" style="border: 1px solid #cdcdcd; color: #FFFFFF; width: 100px; text-align: center" ><strong>{{ $d }}</strong></th>
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
            <td bgcolor="{{ $columnColor }}" style="border: 1px solid #cdcdcd; text-align: center; width: 75px;"  >{{ $item['driver_code'] }}</td>
            <td bgcolor="{{ $columnColor }}">{{ $flag }}</td>
            <td bgcolor="{{ $columnColor }}" style="border: 1px solid #cdcdcd;">{{ $item['driver_name'] }}</td>
            @foreach($item['shift_list'] as $jobs)
                @if(!$jobs['value'])
                <td bgcolor="{{ $jobs['color'] }}" style="border: 1px solid #cdcdcd;"></td>
                @elseif(in_array($jobs['value'][0]['type'], DAY_OFF_CODE))
                <td bgcolor="{{ $jobs['color'] }}" style="border: 1px solid #cdcdcd;">{{ $jobs['value'][0]['name'] }}</td>
                @else
                <td  bgcolor="{{ $jobs['color'] }}" style="border: 1px solid #cdcdcd;">
                    @foreach($jobs['value'] as $job)
                        @php
                            if ($job['name'] == '待機時間')
                                continue;
                        @endphp
                        {{ $job['name'] }}<br>
                    @endforeach
                </td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
