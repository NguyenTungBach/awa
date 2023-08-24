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
            $this->listCourse($browser);
//            $this->createCourse($browser);
//            $this->exportCourse($browser);
//            $this->editCourse($browser);
//            $this->listCourse($browser);
//            $this->deleteCourse($browser);
            $this->importFile($browser);
        });
    }

    private function importFile(Browser $browser)
    {
        $browser->pause(2000)
            ->click('div:nth-child(1) > div.show-icon > i')->pause(2000)
            ->attach('#import-file',base_path('tests/csv/test_import_course.xlsx'))->pause(2000)
            ->click('div:nth-child(4) > .btn-color-active-import')->pause(2000)
            ->waitFor('.toast-body')->pause(2000)
            ->pause(4000);
    }

    private function listCourse(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(1) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(1) > ul > li:nth-child(2) > a')
            ->waitFor('div.show-menu')
            ->pause(4000);
    }

    private function exportCourse(Browser $browser)
    {
        $browser->pause(2000)
            ->click('div:nth-child(2) > div.show-icon > i')
            ->pause(7000);
    }

    private function createCourse($browser){
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
    }
    private function editCourse($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(8) > i')->pause(4000)
            ->click(".btn-save")->pause(2000)
            ->type('#input-course-name',"Bach Update Course")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')
            ->pause(4000);
    }

    private function deleteCourse($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(9) > i')->pause(3000)
            ->click("footer > button.btn.btn-primary")
            ->waitFor('.toast-body')->pause(4000);
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
