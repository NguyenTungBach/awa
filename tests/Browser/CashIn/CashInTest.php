<?php

namespace Tests\Browser\CashIn;

use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCourse;
use App\Models\User;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Helper\Common;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class CashInTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        Artisan::call('migrate:fresh --seed');
        // Tạo lại dữ liệu mẫu cho cash in
        DB::table('driver_courses')->truncate();
        DB::table('cash_ins')->truncate();
        DB::table('cash_in_histories')->truncate();
        DB::table('cash_in_statisticals')->truncate();
//        // Tạo một customer mới
//        $customer = Customer::create([
//            "customer_code" => "0006",
//            "customer_name" => "Customer 06",
//            "closing_date" => 4,
//            "person_charge" => "Person charge 06",
//            "post_code" => "456-7890",
//            "address" => "Address 06",
//            "phone" => "01212341234",
//        ]);
//
//        // Tạo một course mới cho customer
//        $dayForCourse6 = Carbon::now()->addDay()->format("Y-m-d");
//        $course = Course::create([
//            "customer_id"=> $customer->id,
//            "course_name"=> "Course name 6",
//            "ship_date"=> $dayForCourse6,
//            "start_date"=> "09:00",
//            "break_time"=> "00:00",
//            "end_date"=> "10:00",
//            "departure_place"=> "Departure place 06",
//            "arrival_place"=> "Arrival place 0 6",
//            "ship_fee"=> 6000,
//            "associate_company_fee"=> 60,
//            "expressway_fee"=> 60,
//            "commission"=> 60,
//            "meal_fee"=> 60,
//        ]);
//
//        // Tạo driver course
//        DriverCourse::create([
//            "driver_id" => 1,
//            "course_id" => $course->id,
//            "date" => $course->ship_date,
//            "start_time" => $course->start_date,
//            "break_time" => $course->break_time,
//            "end_time" => $course->end_date,
//            "status" => 1,
//        ]);

        $this->browse(function ($browser) {
            $browser->maximize();
            $this->loginAdminGeneral($browser);
            $this->listCashIn($browser);
            $this->createCashIn($browser);
            $this->editCashIn($browser);
            $this->deleteCashIn($browser);
            $this->listCashIn($browser);
            $this->exportCashIn($browser);
        });
    }

    private function listCashIn(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(3) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(3) > ul > li:nth-child(1) > a')
            ->waitFor('div.show-menu')
            ->pause(2000);
    }

    private function exportCashIn(Browser $browser)
    {
        $browser->waitFor('.page-list-cashCiept')->pause(2000)
            ->click('.btn-excel')
            ->pause(8000);
    }

    private function createCashIn($browser){
        $browser->pause(2000);
//        $browser->visit('/data-management/list-driver-create');
        $browser->click('tbody > tr:nth-child(1) > td.text-center.td-control')->pause(6000);
        $browser->click('.btn-edit')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $getDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $this->mapDate($browser, '.input-group.mb-3 .input-group-append', $getDate);
        $browser->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-deposit-day',"2000")->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('#input-payment-method')->pause(1000);
        $browser->click('#input-payment-method > option:nth-child(1)')->pause(1000);
        $browser->type('#input-notes',"test cash in")->pause(2000);
        $browser->type('#input-payment-day',"$getDate"."zxczxc")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-payment-day',"$getDate")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->pause(8000);
    }

    private function editCashIn($browser){
        $browser->click('tbody > tr:nth-child(1) > td.td-cash-edit.td-control > i')->pause(4000);
        $browser->type('#input-deposit-day',"1000")->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->pause(4000);
    }

    private function deleteCashIn($browser){
        $browser->pause(3000)
            ->click('tbody > tr:nth-child(1) > td.td-cash-delete.td-control > i')->pause(3000)
            ->click("div:nth-child(2) > .btn-color-active-import")
            ->waitFor('.toast-body')->pause(4000);
        $browser->pause(6000);
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
