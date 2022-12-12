<table>
    <thead>
    <tr>
        <th>{{ _('CREW ID') }}</th>
        <th>{{ _('総拘束時間') }}</th>
        <th>{{ _('総勤務日数') }}</th>
        <th>{{ _('勤務可能日数') }}</th>
        <th>{{ _('月') }}</th>
        <th>{{ _('火') }}</th>
        <th>{{ _('水') }}</th>
        <th>{{ _('木') }}</th>
        <th>{{ _('金') }}</th>
        <th>{{ _('土') }}</th>
        <th>{{ _('日') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($drivers as $code => $driver)
        <tr>
            <td>{{$driver['driver_code']}}</td>
            <td>{{$driver['workedTimes']}}</td>
            <td>{{$driver['workedDays']}}</td>
            <td>{{$driver['working_day']}}</td>
            <td>{{$driver['mon']?'×':null}}</td>
            <td>{{$driver['tue']?'×':null}}</td>
            <td>{{$driver['wed']?'×':null}}</td>
            <td>{{$driver['thu']?'×':null}}</td>
            <td>{{$driver['fri']?'×':null}}</td>
            <td>{{$driver['sat']?'×':null}}</td>
            <td>{{$driver['sun']?'×':null}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
