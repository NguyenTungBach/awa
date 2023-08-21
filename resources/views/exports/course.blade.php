@php
    $styleDefault = "border: 1px solid #cdcdcd; font-size: 10pt";
    $styleHeader = "border-bottom: 1px solid #000; font-size: 11pt";
@endphp

<style>
    .auto-size {
        table-layout: auto;
        width: 100%;
        word-wrap: break-word;
    }
</style>

<div>
    <table class="auto-size">
        <thead>
            <tr>
                <th colspan="2" style="width: 60px">運行情報一覧</th>
                <th style="{{ $styleHeader }}">{{ $title }}</th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach (config('courses.header_export') as $key => $item)
                    <th>{{ $item }}</th>
                @endforeach
            </tr>
            @foreach ($result as $key => $item)
                <tr>
                    @foreach ($item as $k => $v)
                        <td style="{{ $styleDefault }}" >{{ $v }}</td>
                    @endforeach;
                </tr>
            @endforeach
        </tbody>
    </table>
</div>