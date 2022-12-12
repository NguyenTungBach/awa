<table>
    <thead>
    <tr>
        <th>{{ _('日付') }}</th>
        @for ($date = $startDate; strtotime($date) <= strtotime($endDate); $date = date('Y-m-d', strtotime('+ 1 day', strtotime($date))))
            <th>{{ \Helper\Common::convertFormartTime($date) }}</th>
        @endfor
    </tr>
    </thead>
    <tbody>
        @foreach($dayOffs as $day)
            <tr>
                <td>{{ str_repeat('0', 15 - strlen($day['driver_code'])) . $day['driver_code'] }}</td>
                @foreach($day['daysOfMonth'] as $value)
                    <td>{{ implode(' & ', $value) }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
