<?php

namespace Tests\Browser\Admin;

use App\Models\Driver;
use App\Models\File;
use App\Models\User;
use Facebook\WebDriver\WebDriverBy;
use Helper\Common;
use Tests\DuskTestCase;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ShiftTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    protected $code = '9999';
    protected $name = 'NhanTran';
    protected $nameEdit = 'A';

    public function testGeneral()
    {
        Driver::where('driver_code', $this->code)->delete();

//        Driver::create([
//            "flag"=> "full",
//            "driver_code"=>"9999",
//            "driver_name"=>"NhanTran",
//            "start_date"=>"2021-06-25",
//            "end_date"=>"",
//            "birth_day" => "2020-12-03",
//            "working_day" =>"5",
//            "day_of_week" => "",
//            "working_time" => "",
//            "note" => ""
//
//
//        ]);
        $this->browse(function ($browser) {
            $browser->maximize();
            $browser->pause(5000);
            $this->loginAdmin($browser);
            $this->shiftList($browser);
        });
    }

    private function loginAdmin($browser)
    {
        $browser->visit('/login');
        $browser->type("#user_id", '')->pause(2000);
        $browser->type("#user_id", '1122')->pause(2000);
        $browser->type("#password", '')->pause(2000);
        $browser->type('#password', 'abc12345678')->pause(2000)
            ->click(".login-btn")->pause(10000)
            ->pause(7000)
            ->assertSee('データ管理')->pause(2000)
            ->assertSee('TOSHIN')->pause(2000)
            ->assertSee('シフト表')->pause(2000)
            ->assertSee('社員番号')->pause(2000)
            ->assertSee('社員区分')->pause(2000);
    }

    private function shiftList($browser)
    {
//        $browser->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__back')->pause(5000)
//            ->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__next')->pause(5000)
////            ->click('.picker-month-year > div > div.picker-month-year__next')->pause(10000)
//            ->assertSee('0144')->pause(2000)
//            ->assertSee('管理職・リーダー')->pause(2000)
//            ->assertSee('黒柳　俊久')->pause(2000); // CHECK DATA DRIVER
//
//        $browser->visit("/data-management/list-driver-create")->pause(5000)
//            ->press('#select-type-driver > div:nth-child(1)')
//            ->type('#input-empolyee-number', $this->code)
//            ->type('#input-fullname', $this->name)
//            ->type('#input-date-hire-date', '2022-06-10')
//            ->type('#input-date-date-of-birth', '2022-06-01')
//            ->select('#input-available-days', '1')
//            ->press('#input-fixed-holidays > div:nth-child(1)')
//            ->press('#input-fixed-holidays > div:nth-child(2)')
//            ->scrollIntoView('#input-available-days')->pause(2000)
//            ->type('#input-retirement-date', '2022-10-26')
//            ->click('.btn-save')->waitFor('.toast-header')
//            ->assertSee('成功')->pause(2000);   // new add driver
//
//        $browser->visit("/shift-management/list-shift")->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->assertSee($this->code)->pause(2000)
//            ->assertSee($this->name)->pause(2000); // view
//
//        $driver = Driver::where('driver_code', $this->code)->first();
//        $browser->visit("/data-management/list-driver-edit/" . $driver->id)->pause(10000)
//            ->press('#select-type-driver > div:nth-child(2)')
//            ->type('#input-fullname', $this->nameEdit)
//            ->click('.btn-save')->pause(2000)
//            ->assertSee('成功')->pause(2000);   // change info driver
//
//        $browser->visit("/shift-management/list-shift")->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->assertSee('正社員')->pause(2000)
//            ->assertSee($this->code)->pause(2000)
//            ->assertSee($this->nameEdit)->pause(2000);
//
//        $browser->visit("data-management/list-driver")->pause(7000)
//            ->click('thead > tr > th.position-relative.base-th.th-employee-number')->pause(4000)
//            ->assertButtonEnabled('tbody > tr:nth-child(1) > td:nth-child(5)')
//            ->click('tbody > tr:nth-child(1) > td:nth-child(5)')->pause(2000)
//            ->assertButtonEnabled('#modal-delete___BV_modal_footer_ > button.btn.btn-primary')
//            ->click('#modal-delete___BV_modal_footer_ > button.btn.btn-primary')->pause(2000); // delete driver
//
//        $browser->visit("/shift-management/list-shift")->pause(10000)
//            ->assertButtonEnabled('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->assertButtonEnabled('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000);
//
//        $browser->assertButtonEnabled('.btn-excel')
//            ->assertButtonEnabled('.btn-pdf')
//            ->click('.btn-excel')->pause(5000)
//            ->click('.btn-pdf')->pause(5000)
//            ->click('.btn-list-shift-month')->pause(5000)
//            ->pause(5000);
//
//
//        $browser->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__back')->pause(5000)
//            ->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__next')->pause(5000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-type-employee')->pause(10000)
//            ->click('thead > tr:nth-child(2) > th.th-type-employee')->pause(10000);
//
//        $browser->refresh()->pause(10000)  // 1 tuần
//            ->assertButtonEnabled('.show-icon')
//            ->click('.show-icon')->pause(2000)
//            ->mouseover('#calendar-multiple-week')->pause(1000)
//            ->assertSee('期間を選択してください')
//            ->click('.b-calendar:nth-child(1)  div[data-date="2022-10-01"]')->pause(2000)
//            ->click('.popover-body > div > div:nth-child(2) div[data-date="2022-10-07"]')->pause(2000)
//            ->mouseover('.text-center')->pause(2000)
//            ->click('#modal-ai___BV_modal_body_ > div:nth-child(3) > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(1000)
//            ->assertSee('上書きしてシフトを作成しますか')
//            ->click('#modal-confirm-ai___BV_modal_footer_ > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(150000);
//
//        $browser->refresh()->pause(10000)  //2 tuần
//            ->assertButtonEnabled('.show-icon')
//            ->click('.show-icon')->pause(2000)
//            ->mouseover('#calendar-multiple-week')->pause(1000)
//            ->assertSee('期間を選択してください')
//            ->click('.b-calendar:nth-child(1)  div[data-date="2022-10-01"]')->pause(2000)
//            ->click('.popover-body > div > div:nth-child(2) div[data-date="2022-10-14"]')->pause(2000)
//            ->mouseover('.text-center')->pause(2000)
//            ->click('#modal-ai___BV_modal_body_ > div:nth-child(3) > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(1000)
//            ->click('#modal-confirm-ai___BV_modal_footer_ > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(400000);
//
//        $browser->refresh()->pause(10000)  //3 tuần
//            ->assertButtonEnabled('.show-icon')
//            ->click('.show-icon')->pause(2000)
//            ->mouseover('#calendar-multiple-week')->pause(1000)
//            ->assertSee('期間を選択してください')
//            ->click('.b-calendar:nth-child(1)  div[data-date="2022-10-01"]')->pause(2000)
//            ->click('.popover-body > div > div:nth-child(2) div[data-date="2022-10-21"]')->pause(2000)
//            ->mouseover('.text-center')->pause(2000)
//            ->click('#modal-ai___BV_modal_body_ > div:nth-child(3) > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(1000)
//            ->click('#modal-confirm-ai___BV_modal_footer_ > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(600000);
//
//        $browser->refresh()->pause(10000) //4 tuần
//            ->assertButtonEnabled('.show-icon')
//            ->click('.show-icon')->pause(2000)
//            ->mouseover('#calendar-multiple-week')->pause(1000)
//            ->assertSee('期間を選択してください')
//            ->click('.b-calendar:nth-child(1)  div[data-date="2022-10-01"]')->pause(2000)
//            ->click('.popover-body > div > div:nth-child(2) div[data-date="2022-10-28"]')->pause(2000)
//            ->mouseover('.text-center')->pause(2000)
//            ->click('#modal-ai___BV_modal_body_ > div:nth-child(3) > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(1000)
//            ->click('#modal-confirm-ai___BV_modal_footer_ > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(1000000);

        $browser->refresh()->pause(10000)
            ->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__next')->pause(1000)
            ->click('.picker-month-year > div > div.picker-month-year__next')->pause(5000)
            ->assertButtonEnabled('.show-icon')
            ->click('.show-icon')->pause(2000)
            ->mouseover('#calendar-multiple-week')->pause(1000)
            ->assertSee('期間を選択してください')
            ->click('.b-calendar:nth-child(1) button[title="Next month"]')->pause(2000)
            ->click('.popover-body > div > div:nth-child(2) button[title="Next month"]')->pause(2000)
            ->click('.b-calendar:nth-child(1)  div[data-date="2022-11-05"]')->pause(2000)
            ->click('.popover-body > div > div:nth-child(2) div[data-date="2022-11-11"]')->pause(2000)
            ->mouseover('.text-center')->pause(2000)
            ->click('#modal-ai___BV_modal_body_ > div:nth-child(3) > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(1000)
            ->click('#modal-confirm-ai___BV_modal_footer_ > button.btn.btn-color-active.btn-modal.btn-secondary.rounded-pill')->pause(150000);



        $browser->click('.picker-month-year > div > div.picker-month-year__next')->pause(10000)
            ->click('.picker-month-year > div > div.picker-month-year__back')->pause(10000)
            ->click('.btn-list-shift-month')->pause(8000)
            ->click('.btn-list-shift-week')->pause(8000)
            ->click('div.list-shift > div.list-shift__control > div > div:nth-child(1) > div > div > button:nth-child(2)')->pause(10000)
            ->assertSee('1日〜末日までのデータを表示')
            ->assertSee('従業員名')
            ->assertSee('総拘束時間')
            ->assertSee('総乗車時間')
            ->assertSee('残業時間')
            ->assertSee('出勤日数')
            ->assertSee('0144')->pause(2000)
            ->assertSee('管理職・リーダー')->pause(2000)
            ->assertSee('黒柳　俊久')->pause(2000)
            ->click('thead > tr > th.text-center.driver-code')->pause(100000)
            ->click('thead > tr > th.text-center.driver-flag')->pause(100000)
            ->assertButtonEnabled('.btn-excel')
            ->assertButtonEnabled('.btn-pdf')
            ->click('div.list-shift > div.list-shift__header > div > div.item-function.btn-excel')->pause(4000)
            ->click('div.list-shift > div.list-shift__header > div > div.item-function.btn-pdf')->pause(4000);


        $browser->click('.btn-list-shift-month')->pause(8000)
            ->click('div.list-shift > div.list-shift__control > div > div:nth-child(1) > div > div > button:nth-child(3)')
            ->assertSee('前月21日〜20日までのデータを表示')
            ->assertSee('従業員名')
            ->assertSee('総拘束時間')
            ->assertSee('総乗車時間')
            ->assertSee('残業時間')
            ->assertSee('出勤日数')
            ->assertSee('0144')->pause(2000)
            ->assertSee('管理職・リーダー')->pause(2000)
            ->assertSee('黒柳　俊久')->pause(2000)
            ->click('thead > tr:nth-child(2) > th.driver-code')->pause(10000)
            ->click('thead > tr:nth-child(2) > th.driver-flag')->pause(10000)
            ->assertButtonEnabled('.btn-excel')
            ->assertButtonEnabled('.btn-pdf')
            ->click('div.list-shift > div.list-shift__header > div > div.item-function.btn-excel')->pause(4000)
            ->click('div.list-shift > div.list-shift__header > div > div.item-function.btn-pdf')->pause(4000);

        // okie con ze

        $browser->refresh()->pause(10000)
            ->click('div.list-shift > div.list-shift__control > div > div:nth-child(2) > div > button')->pause(3000)
            ->click('.node-base-hover')->pause(3000)
            ->click('#modal-detail___BV_modal_body_ > div.modal-detail-node-control > button.btn.btn-change.btn-secondary.rounded-pill')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div.select-type > div > div.input-group-prepend')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div > div > h6 > span')->pause(2000)
            ->select('.custom-select', 'S-2')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-control > button.btn.btn-save.btn-secondary.rounded-pill')->pause(1000)
            ->assertSee('入力されていない項目があります')
            ->click('.select-multiple:nth-child(1) input:nth-child(1)')->pause(2000)
            ->click('.select-multiple:nth-child(1) .select-option:nth-child(1) .list-option > li:nth-child(22)')->pause(1000)
            ->click('.select-multiple:nth-child(1) .select-option:nth-child(2) .list-option > li:nth-child(1)')->pause(1000)
            ->click('.edit-node')->pause(1000)


            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(2) > div > div.col-sm-9 > div > input')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(2) .select-option:nth-child(1) .list-option > li:nth-child(19)')->pause(1000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(2) .select-option:nth-child(2) .list-option > li:nth-child(1)')->pause(1000)
            ->click('.edit-node')->pause(1000)



            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(3) > div > div.col-sm-9 > div input')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(3) .select-option:nth-child(1)  .list-option > li:nth-child(1)')->pause(1000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(3) .select-option:nth-child(2) .list-option > li:nth-child(1)')->pause(1000)
            ->click('.edit-node')->pause(1000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-control > button.btn.btn-save.btn-secondary.rounded-pill')->pause(1000)

            ->assertSee('終業時間は始業時間より後の時間を選択してください。')

            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(2) > div > div.col-sm-9 > div > input')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(2) .select-option:nth-child(1) .list-option > li:nth-child(23)')->pause(1000)
////            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div:nth-child(2) > div > div > div:nth-child(2) .select-option:nth-child(2) .list-option > li:nth-child(1)')->pause(1000)
            ->click('.edit-node')->pause(1000)

            ->pause(3000)
            ->clickAtXPath('//*[@id="modal-edit___BV_modal_body_"]/div[2]/div[2]/div/h6/span')->pause(1000)
//            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.zone-add.disabled > div > h6 > span')
//            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div.select-type > div > div.input-group-prepend')->pause(2000)
            ->select('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div:nth-child(2) > div.select-type > div > select', '00008')->pause(2000)


            ->clickAtXPath('//*[@id="modal-edit___BV_modal_body_"]/div[2]/div[3]/div/h6/span')->pause(2000)
////            ->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div.select-type > div > div.input-group-prepend')->pause(2000)
            ->select('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div:nth-child(3) > div.select-type > div > select', '00007')->pause(2000)
            ->click('#modal-edit___BV_modal_body_ > div.edit-control > button.btn.btn-save.btn-secondary.rounded-pill')->pause(1000)
            ->assertSee('この時間は既に登録されています。')

            ->clickAtXPath('//*[@id="modal-edit___BV_modal_body_"]/div[2]/div[1]/div[1]/div/div/div/i')->pause(2000)
            ->clickAtXPath('//*[@id="modal-edit___BV_modal_body_"]/div[2]/div[3]/div[1]/div/div/div/i')->pause(2000)

            ->click('#modal-edit___BV_modal_body_ > div.edit-control > button.btn.btn-save.btn-secondary.rounded-pill')->pause(3000)

            ->mouseover('.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2)')->pause(3000)
            ->click('.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(3)')
            ->assertSee('このページから移動しますか？入力したデータは保存されません。')
            ->click('.btn btn-secondary')->pause(2000)
            ->click('.list-shift > div.list-shift__control > div > div > div > button.btn.btn-save.btn-secondary.rounded-pill');
    }


}
