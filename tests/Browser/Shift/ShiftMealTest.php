<?php

namespace Tests\Browser\Shift;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShiftMealTest extends DuskTestCase
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
            $this->listMeal($browser);
            $this->exportExcelMeal($browser);
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

    private function listMeal(Browser $browser)
    {
        $browser->click('div:nth-child(1) > div > div > button:nth-child(3)')->pause(6000);
    }

    private function exportExcelMeal($browser)
    {
        $browser->waitFor('div:nth-child(4) > .btn-excel')->pause(2000);
        $browser->click('div:nth-child(4) > .btn-excel')->pause(10000);
        $browser->click('#select-closing-date')->pause(2000);
        $browser->click('#input-closing-date > option:nth-child(2)')->pause(2000);
        $browser->click('div:nth-child(3) > .btn-color-active-import')->pause(2000);
        $browser->waitFor('div:nth-child(4) > .btn-excel')->pause(2000);
        $browser->click('div:nth-child(4) > .btn-excel')->pause(10000);
    }
}
