<table>
    <thead>
    <tr>
        <th>{{ _('運行ルート') }}</th>
        @for ($date = $startDate; strtotime($date) <= strtotime($endDate); $date = date('Y-m-d', strtotime('+ 1 day', strtotime($date))))
            <th>{{\Helper\Common::convertFormartTime($date)}}</th>
        @endfor
    </tr>
    <tr>
        <th>{{ _('曜日') }}</th>
        @foreach($schedules[0]['course_schedules'] as $keySchedule => $valueSchedule)
            <th>{{$valueSchedule['lunar_jp']}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach($schedules as $course)
            @if ($course['flag'] == \App\Models\Course::COURSE_FLAG_NO)
            <tr>
                <td>{{$course['course_code']}}</td>
                @foreach($course['course_schedules'] as $key => $value)
                    <td>{{$value['status'] == 'on'?'○':null }}</td>
                @endforeach
            </tr>
            @endif
        @endforeach
    </tbody>
</table>
