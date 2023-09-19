<?php

namespace Tests\Browser\Shift;

use Illuminate\Support\Facades\Artisan;
use Tests\DuskTestCase;

class ShiftTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        Artisan::call('migrate:fresh --seed');
        $this->browse(function ($browser) {
            $browser->maximize();
            $this->loginAdminGeneral($browser);
            $browser->pause(5000);
            $this->edit($browser);
            $this->exportExcel($browser);
        });
    }

//    private function list($browser)
//    {
//        $browser->click('div:nth-child(1) > div > div > .btn-secondary')->pause(4000);
//        $browser->waitFor('div.show-menu > ul > li:nth-child(2) > span');
//        $browser->click('div:nth-child(2) > div > div.show-icon > i')->pause(6000);
//    }

    private function edit($browser)
    {
        $browser->waitFor('div:nth-child(2) > div:nth-child(1) > button')->pause(2000);
        $browser->click('div:nth-child(2) > div:nth-child(1) > button')->pause(4000);
        $browser->click('#node-1-1-0001')->pause(2000);
        $browser->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift')->pause(2000);
        $browser->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div > div')->pause(2000);
        $browser->click('#modal-edit___BV_modal_body_ > div.edit-node-list-shift > div.edit-item > div > div > .custom-select > option:nth-child(1)')->pause(2000);
        $browser->click('#modal-edit___BV_modal_body_ > div.edit-control > button.btn.btn-save.btn-secondary.rounded-pill')->pause(2000);
        $browser->click('div.list-shift > div.list-shift__control > div > div > div > .btn-save')->pause(2000);
        $browser->waitFor('.zone-table')->pause(4000);
    }

    private function exportExcel($browser)
    {
        $browser->waitFor('div:nth-child(1) > .btn-excel')->pause(2000);
        $browser->click('div:nth-child(1) > .btn-excel')->pause(10000);
//        $browser->click('#input-closing-date')->pause(2000);
//        $browser->click('#input-closing-date > option:nth-child(2)')->pause(2000);
//        $browser->click('div:nth-child(3) > .btn-color-active-import')->pause(2000);
//        $browser->waitFor('div:nth-child(1) > .btn-excel')->pause(2000);
//        $browser->click('div:nth-child(1) > .btn-excel')->pause(10000);
    }

//    private function loginDriver($browser)
//    {
//        $browser->visit('/login');
//        $browser->type("#user_id", '')->pause(2000);
//        $browser->type("#user_id", '2233')->pause(2000);
//        $browser->type("#password", '')->pause(2000);
//        $browser->type('#password', 'abc12345678')->pause(2000)
//            ->click(".login-btn")->pause(10000)
//            ->pause(7000)
//            ->assertSee('TOSHIN')->pause(2000)
//            ->assertSee('シフト表')->pause(2000)
//            ->assertSee('社員番号')->pause(2000)
//            ->assertSee('社員区分')->pause(2000);
//    }

    private function shiftList($browser)
    {
        $browser->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__back')->pause(5000)
            ->assertButtonEnabled('.picker-month-year > div > div.picker-month-year__next')->pause(5000)
            ->click('.picker-month-year > div > div.picker-month-year__back')->pause(10000)
            ->assertSee('0144')->pause(2000)
            ->assertSee('休日')->pause(2000)
            ->assertSee('管理職・リーダー')->pause(2000)
            ->assertSee('黒柳　俊久')->pause(2000);

        $browser->assertButtonEnabled('.btn-back')
            ->assertButtonEnabled('.btn-next')
            ->click('.btn-back')->pause(10000)
            ->click('.btn-next')->pause(10000)
            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
            ->click('thead > tr:nth-child(2) > th.th-employee-number')->pause(10000)
            ->click('thead > tr:nth-child(2) > th.th-type-employee')->pause(10000)
            ->click('thead > tr:nth-child(2) > th.th-type-employee')->pause(10000);

        $browser->assertButtonEnabled('.btn-excel')
            ->assertButtonEnabled('.btn-pdf')
            ->click('.btn-excel')->pause(5000)
            ->click('.btn-pdf')->pause(5000)
            ->click('.btn-list-shift-month')->pause(5000)
            ->pause(5000000);
    }
}
