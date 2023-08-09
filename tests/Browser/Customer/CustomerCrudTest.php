<?php

namespace Tests\Browser\Customer;

use App\Models\Driver;
use App\Models\User;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Helper\Common;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class CustomerCrudTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        Artisan::call('migrate:fresh --seed');
//        DB::table('drivers')->where('driver_code', "Bach001")->delete();
        $this->browse(function ($browser) {
            $browser->maximize();
            $this->loginAdminGeneral($browser);
            $this->listCustomer($browser);
            $this->createCustomer($browser);
            $this->editCustomer($browser);
            $this->listCustomer($browser);
            $this->deleteCustomer($browser);
        });
    }

    private function listCustomer(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(2) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(1) > a')
            ->waitFor('div.show-menu')
            ->pause(2000);
    }

    private function createCustomer($browser){
        $browser->pause(2000);
        $browser->click('div:nth-child(2) > div > button')->pause(2000);
        $browser->click('#select-type-driver > div:nth-child(1) > label')->pause(2000);
        $browser->type('#input-course-id',"001122")->pause(1000);
        $browser->type('#input-course-name','Bach Customer')->pause(1000);
        $browser->click('#customer-closing-day')->pause(1000);
        $browser->click('#customer-closing-day > option:nth-child(4)')->pause(1000);
        $browser->type('#input-character','abc')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')
            ->assertSee('Create driver success')->pause(4000);
    }
    private function editCustomer($browser){
        $browser->click('tbody > tr:nth-child(5) > td:nth-child(5) > i')->pause(5000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-fullname','Bach Driver Edit')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')
            ->assertSee('Update driver success')->pause(4000);
    }

    private function deleteCustomer($browser){
        $browser->pause(5000)
            ->click('tbody > tr:nth-child(5) > td:nth-child(6) > i')->pause(3000)
            ->click("footer > button.btn.btn-primary")
            ->waitFor('.toast-body')->pause(4000);
//        $browser->click('.btn-save')->waitFor('.toast-body')
//            ->assertSee('Update driver success')->pause(4000);
    }

    private function mapDate($modal, $selector, $date)
    {
        $modal->pause(500);
        $modal->click($selector);
        $modal->pause(500);
        $modal->click('button[title="Current month"]');
//        $modal->click('button[title="Next month"]');
        $modal->pause(500);
        $modal->click('div[data-date="' . $date . '"]');
        $modal->pause(500);
    }
}
