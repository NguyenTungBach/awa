<?php

namespace Tests\Browser\Course;

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

class CourseCrudTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        Artisan::call('migrate:fresh --seed');
        DB::table('courses')->truncate();
        $this->browse(function ($browser) {
            $browser->maximize();
            $this->loginAdminGeneral($browser);
            $this->list($browser);
            $this->create($browser);
//            $this->editCustomer($browser);
//            $this->list($browser);
//            $this->deleteCustomer($browser);
        });
    }

    private function list(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(1) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(1) > ul > li:nth-child(2) > a')
            ->waitFor('div.show-menu')
            ->pause(4000);
    }

    private function create($browser){
        $browser->click('.title-edit')->pause(4000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $getDate = Carbon::now()->format('Y-m-d');
        $this->mapDate($browser, '.input-group-append', $getDate);
        $browser->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-course-name',"Bach Test Course")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('div.row > div:nth-child(1) > div > div > div')->pause(2000);
        $browser->click('div:nth-child(1) > div > div > div > div > div > div:nth-child(1) > ul > li:nth-child(9)')->pause(2000);
        $browser->click('div:nth-child(1) > div > div > div > div > div > div:nth-child(2) > ul > li:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('div:nth-child(1) > div.col-sm-12> div > div.row > div:nth-child(2) > div > div > div')->pause(2000);
        $browser->click('div:nth-child(2) > div > div > div > div > div > div:nth-child(1) > ul > li:nth-child(19)')->pause(2000);
        $browser->click('div:nth-child(2) > div > div > div > div > div > div:nth-child(2) > ul > li:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('div.row > div:nth-child(3) > div > div > div')->pause(2000);
        $browser->click('div:nth-child(3) > div > div > div > div > div > div:nth-child(1) > ul > li:nth-child(16)')->pause(2000);
        $browser->click('div:nth-child(3) > div > div > div > div > div > div:nth-child(2) > ul > li:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('#input-customer-name')->pause(2000);
        $browser->click('#input-customer-name > option:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-depature-place',"Dong Da")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-arrival_place',"Kim Ma")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-freight-cost',"10000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->pause(5000);
//        $browser->type('#input-course-id',"001122")->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#input-course-name','Bach Customer')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->click('#customer-closing-day')->pause(1000);
//        $browser->click('#customer-closing-day > option:nth-child(4)')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#input-course-manager','abc')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#postCode-first','123')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#postCode-second','4544')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#input-course-address','address test create')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#input-course-phone','01234567892')->pause(1000);
//        $browser->type('#input-course-name','Bach Customerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#input-course-name','Bach Customer')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->assertSee('Create customer success')
//            ->pause(4000);
    }
    private function editCustomer($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(4) > i')->pause(5000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-course-name','Bach Customer Update')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')
            ->pause(4000);
    }

    private function deleteCustomer($browser){
        $browser
            ->click('tbody > tr:nth-child(1) > td:nth-child(5) > i')->pause(3000)
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
