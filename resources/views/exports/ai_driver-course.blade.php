<table>
    <thead>
    <tr>
        <th>{{ _('CREW名') }}</th>
        @foreach($courses as $code)
            <th>{{ _($code) }}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($drivers as $driver)
        <tr>
            <td>{{ str_repeat('0', 15 - strlen($driver['driver_code'])) . $driver['driver_code']}}</td>
            @foreach($courses as $code)
                <td>
                    @php
                    $hasCourse = '×';
                    if ($driver['driver_course']) {
                        foreach ($driver['driver_course'] as $info) {
                            if ($info['course_code'] == $code) {
                                $hasCourse = '○';
                            }
                        }
                    }
                    @endphp
                    {{ $hasCourse }}
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
