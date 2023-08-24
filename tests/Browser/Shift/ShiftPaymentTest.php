<?php

namespace Tests\Browser\Shift;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShiftPaymentTest extends DuskTestCase
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
            $this->loginAdminGeneral($browser);
            $browser->pause(5000);
            $this->list($browser);
            $this->finalClosing($browser);
            $this->exportExcel($browser);
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

    private function list(Browser $browser)
    {
        $browser->click('div:nth-child(1) > div > div > button:nth-child(4)')->pause(6000);
    }

    private function finalClosing($browser)
    {
        $browser->waitFor('div:nth-child(2) > .btn-temporary')->pause(2000);
        $browser->click('div:nth-child(2) > .btn-temporary')->pause(4000);
        $browser->click('div:nth-child(2) > .btn.btn-final')->pause(4000);
    }

    private function exportExcel($browser)
    {
        $browser->waitFor('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(3) > div')->pause(2000);
        $browser->click('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(3) > div')->pause(10000);
    }
}
