<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Export</title>
    <style type="text/css">
        body{
            font-family: 'Roboto Condensed', sans-serif;
        }
        .m-0{
            margin: 0px;
        }
        .p-0{
            padding: 0px;
        }
        .pt-5{
            padding-top:5px;
        }
        .mt-10{
            margin-top:10px;
        }
        .text-center{
            text-align:center !important;
        }
        .w-100{
            width: 100%;
        }
        .w-50{
            width:50%;
        }
        .w-85{
            width:85%;
        }
        .w-33{
            width:33%;
        }
        .w-15{
            width:15%;
        }
        .logo img{
            width:200px;
            height:60px;
        }
        .gray-color{
            color:#5D5D5D;
        }
        .text-bold{
            font-weight: bold;
        }
        .border{
            border:1px solid black;
        }
        table tr,th,td{
            border: 1px solid #d2d2d2;
            border-collapse:collapse;
            padding:7px 8px;
        }
        table tr th{
            background: #F4F4F4;
            font-size:15px;
        }
        table tr td{
            font-size:13px;
        }
        table{
            border-collapse:collapse;
        }
        .box-text p{
            line-height:10px;
        }
        .float-left{
            float:left;
        }
        .total-part{
            font-size:16px;
            line-height:12px;
        }
        .total-right p{
            padding-right:20px;
        }
    </style>
<body>
<div class="head-title">
    <div class="w-33 float-left">
        <p class="text-center m-0 p-0">&nbsp;</p>
    </div>
    <div class="w-33 float-left mt-0">
        <h1 class="text-center m-0 p-0">請求書</h1>
    </div>
    <div class="w-33 float-left mt-0">
        <p class="m-0 pt-5 text-bold" style="font-size: 10px;text-align: right">ページ {{$page + 1}} '{{$data['invoice_date']}}</p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="add-detail mt-10">
    <div class="w-33 float-left mt-0">
        <p class="m-0 pt-5 text-bold w-100">〒 {{$data['post_code']}}</p>
        <p class="m-0 pt-5 text-bold w-100">{{$data['address']}}</p>
        <p class="m-0 pt-5 text-bold w-100">{{$data['customer_name']}} 御中</p>
    </div>
    <div class="w-33 float-left logo mt-0">
        <p class="m-0 pt-5 text-bold w-100 text-center" style="font-size: 10px">{{$data['aboutDateJapan']}}</p>
        <p class="m-0 pt-5 text-bold w-100 text-center">未日締　{{$data['month_choose']}}月分</p>
    </div>
    <div class="w-33 float-left logo mt-0">
        <pre class="m-0 text-bold w-100">〒770-8001
徳島県徳島市津田海岸町11125-23
適格事業者登録番号：T2480001000065
阿波急行運輸株式会社
TEL (088) 662-2226㈹　FAX (088) 662-2216
取引銀行　　徳島大正銀行　本店営業部
当座預金　６４９６３５１
        </pre>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-50 mt-10">
    <div>下記の通り御請求申し上げます。</div>
    <div class="w-100 text-center float-left mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-25">運賃合計</th>
                <th class="w-25">非課税額</th>
                <th class="w-25">消費税</th>
                <th class="w-25">今回御請求額</th>
            </tr>
            <tr>
                <td style="text-align: right">
                    {{$data['total_ship_fee_by_closing_date'] == "" ? 0 : number_format($data['total_ship_fee_by_closing_date'])}}
                </td>
                <td style="text-align: right">
                    0
                </td>
                <td style="text-align: right">
                    {{($data['total_ship_fee_by_closing_date'] == "" ? 0 : number_format($data['total_ship_fee_by_closing_date'] * 0.1))}}
                </td>
                <td style="text-align: right">
                    {{number_format(($data['total_ship_fee_by_closing_date'] == "" ? 0 : $data['total_ship_fee_by_closing_date']) + (($data['total_ship_fee_by_closing_date'] == "" ? 0 : $data['total_ship_fee_by_closing_date'] * 0.1)))}}
                </td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th>年月日</th>
            <th>車番</th>
            <th>発地</th>
            <th>着地</th>
            <th>品名</th>
            <th>重量/Kg</th>
            <th>基本運賃</th>
            <th>摘要</th>
        </tr>
        <?php
        if (count($data['date_ship_fee'] ?? []) != 0){
        foreach ($chunks as $course){
        ?>
        <tr>
            <td style="text-align: center">{{$course['ship_date']}}</td>
            <td style="text-align: center">{{$course['car']}}</td>
            <td style="text-align: center">{{$course['departure_place']}}</td>
            <td style="text-align: center">{{$course['arrival_place']}}</td>
            <td style="text-align: center"></td>
            <td style="text-align: center">{{$course['weight']}}</td>
            <td style="text-align: center">{{$course['ship_fee'] == '' ? '' : number_format($course['ship_fee'])}}</td>
            <td style="text-align: center"></td>
        </tr>
        <?php
        }
        }
        ?>
        <?php
        if ($page == 0){
            $dem= 0;
            foreach ($data['date_ship_fee'] as $course){
                $dem= $dem + $course['ship_fee'];
            }
        ?>
        <tr>
            <td style="text-align: center"></td>
            <td style="text-align: center"></td>
            <td style="text-align: center"></td>
            <td style="text-align: center"></td>
            <td style="text-align: center">
                <p>合計</p>
            </td>
            <td style="text-align: center"></td>
            <td style="text-align: center">{{number_format($dem)}}</td>
            <td style="text-align: center"></td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
</body>
</html>
