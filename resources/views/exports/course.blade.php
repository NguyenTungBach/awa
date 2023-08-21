@php
    $styleDefault = "border: 1px solid #cdcdcd; font-size: 10pt";
    $styleHeader = "border-bottom: 1px solid #000; font-size: 11pt";
@endphp

<div>
    <table>
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
                <th>ID</th>
                <th>運行日</th>
                <th>運行名</th>
                <th style="width: 100px;">始業時間</th>
                <th style="width: 100px;">終業時間</th>
                <th style="width: 120px;">休憩時間</th>
                <th style="width: 120px;">荷主名</th>
                <th style="width: 120px;">発地</th>
                <th style="width: 120px;">着地</th>
                <th style="width: 120px;">運賃</th>
                <th style="width: 180px;">協力会社支払金額</th>
                <th style="width: 180px;">高速道路・フェリー料金</th>
                <th style="width: 120px;">歩合</th>
                <th style="width: 120px;">食事補助金額</th>
                <th style="width: 120px;">メモ</th>
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