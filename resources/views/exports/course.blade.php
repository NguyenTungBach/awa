<style type="text/css">
    .custom-row {
        border-bottom: 1px SOLID #000000;
        background-color: #ffffff;
        color: #000000;
        font-size: 20pt;
    }
    .custom-header {
        background: #ff765e;
        color: #ffffff;
        font-size: 11pt;
    }
    .custom-item {
        background: #ffffff;
        color: #000000;
        font-size: 11pt;
    }
</style>
@php
    $headerColor = '#ff765e';
    $itemColor = '#ffffff';
@endphp
<div>
    <table>
        <tbody>
            <tr style="height: 19px">
                <th bgcolor="{{ $itemColor }}" style="color: #000000; font-size: 20pt; border-bottom: 1px SOLID #000000; height: 19px" class="custom-row" colspan="2">運行情報一覧</th>
            </tr>
            <tr></tr>
            <tr>
                @foreach (config('courses.header_export') as $key => $item)
                    <th bgcolor="{{ $headerColor }}" style="color: #FFFFFF; font-size: 11pt" class="custom-header" >{{ $item }}</th>
                @endforeach
            </tr>
            @foreach ($result as $key => $item)
                <tr>
                    @foreach ($item as $k => $v)
                        <td bgcolor="{{ $itemColor }}" style="color: #000000; font-size: 11pt" class="custom-item">{{ $v }}</td>
                    @endforeach;
                </tr>
            @endforeach
        </tbody>
    </table>
</div>