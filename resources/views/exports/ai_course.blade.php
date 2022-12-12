<table>
    <thead>
    <tr>
        <th>{{ _('コースID') }}</th>
        <th>{{ _('始業時間') }}</th>
        <th>{{ _('終業時間') }}</th>
        <th>{{ _('休憩時間') }}</th>
        <th>{{ _('ポイント') }}</th>
        <th>{{ _('スポット便') }}</th>
        <th>{{ _('ショート便') }}</th>
        <th>{{ _('専任コース') }}</th>

    </thead>
    <tbody>
    @foreach($courses as $code => $course)
        <tr>
            <td>{{ $course['course_code']}}</td>
            <td >{{ $course['start_time']}}</td>
            <td>{{ $course['end_time']}}</td>
            <td>{{ $course['break_time'] }}</td>
            <td>{{ $course['point']}}</td>
            <td>{{ $course['flag'] == 'yes'? '○': ''}}</td>
            <td>{{ $course['pot'] == 'yes'? '○': ''}}</td>
            <td>{{ $course['owner']? str_repeat('0', 15 - strlen($course['owner']['driver_code'])) . $course['owner']['driver_code']: ''}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
