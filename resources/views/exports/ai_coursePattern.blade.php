<table>
    <thead>
    <tr>
        <th>{{ _('運行ルート') }}</th>
        @foreach($coursePatterns as $course)
           <th> {{$course['course_code']}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($coursePatterns as $course)
        <tr>
            <td>{{$course['course_code']}}</td>
            @foreach($course['course_patterns'] as $data)
                <td>{{ $data['status'] == 'yes'?'○':'×' }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
