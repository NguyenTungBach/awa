<?php

namespace Tests\Browser\Shift;

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
        $this->browse(function ($browser) {
            $browser->maximize();
            $browser->pause(7000);
            $this->loginAdminGeneral($browser);
//            $this->shiftList($browser);
        });
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