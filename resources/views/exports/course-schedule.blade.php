<table>
    <thead>
    <tr>
        <th><strong></strong></th>
        <th><strong>{{ _('運行ルート') }}</strong></th>
        @foreach($dates as $dt)
            <th><strong>{{ $dt }}</strong></th>
        @endforeach
    </tr>
    <tr>
        <th><strong>{{ _('コースID') }}</strong></th>
        <th><strong>{{ _('曜日') }}</strong></th>

        @foreach($daysInWeek as $dt)
            <th><strong>{{ $dt }}</strong></th>
        @endforeach
    </tr>
    </thead>

    <tbody>
    @foreach($items as $course)
        <tr>
            <td>{{ $course['course_code'] }}</td>
            <td>{{ $course['course_name'] }}</td>

            @foreach($course['course_schedules'] as $schedule)
            @php
            $status = $schedule['status'] == 'on' ? 'o': ($schedule['status'] == 'off'? 'x': 'x');
            @endphp
            <td stysle="background:{{ $status == 'x' || $status == '' ? '#cdcdcd': '' }};">{{ $status }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
