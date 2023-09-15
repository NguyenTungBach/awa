<?php
return [
    'header' => [
        '運行日', // 0 ship_date 0
        '従業員名', // 1 driver_id
        '車両番号', // 2 vehicle_number
        '始業時間', // 3 start_date
        '終業時間', // 4 end_date
        '休憩時間', // 5 break_time
        '荷主名', // 6 customer_id
        '発地', // 7 departure_place
        '着地', // 8 arrival_place
        '品名', // 9 item_name
        '数量', // 10 quantity
        '単価', // 11 price
        '重量', // 12 weight
        '運賃', // 13 ship_fee
        '協力会社支払金額', // 14 associate_company_fee
        '高速道路・フェリー料金', // 15 expressway_fee
        '歩合', // 16 commission
        '食事補助金額', // 17 meal_fee
        'メモ', // 18 note
    ],
    'comma' => ', ',
    'header_export' => [
        'ID', // id
        '運行日', //ship_date
        '運行名', // course_name
        '始業時間', // start_date
        '終業時間', // end_date
        '休憩時間', // break_time
        '荷主名', // customer_name
        '発地', // departure_place
        '着地', // arrival_place
        '運賃', // ship_fee
        '協力会社支払金額', // associate_company_fee
        '高速道路・フェリー料金', // expressway_fee
        '歩合', // commission
        '食事補助金額', // meal_fee
        'メモ', // note
    ]
];