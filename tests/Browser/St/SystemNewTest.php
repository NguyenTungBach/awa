<?php

namespace Tests\Browser\St;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;

class SystemNewTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed --class=UserSeeder');
        Artisan::call('db:seed --class=DriverSeeder');
        Artisan::call('db:seed --class=CustomerSeeder');
        Artisan::call('db:seed --class=CourseSeeder');
        $this->browse(function ($browser) {
            $browser->maximize();
            $this->loginAdminGeneral($browser);

            // Customer
            $this->listCustomer($browser);
            $this->createCustomer($browser);
            $this->editCustomer($browser);
            $this->listCustomer($browser);
//            $this->deleteCustomer($browser);

            // Driver
            $this->listDriver($browser);
            $this->createDriver($browser);
            $this->editDriver($browser);
            $this->listDriver($browser);
//            $this->deleteDriver($browser);

            // Course
            $this->listCourse($browser);
            $this->createCourse($browser);
            $this->exportCourse($browser);
            $this->editCourse($browser);
            $this->listCourse($browser);
//            $this->deleteCourse($browser);

            // Shift
            $browser->pause(3000);
            $this->listShift($browser);
            $this->editShift($browser);
            $this->exportExcelShift($browser);

            // Shift High Way Fee
            $browser->pause(3000);
            $this->listShift($browser);
            $this->listHighWayFee($browser);
            $this->exportExcelHighWayFee($browser);

            // Shift High Way Fee
            $browser->pause(3000);
            $this->listShift($browser);
            $this->listMeal($browser);
            $this->exportExcelMeal($browser);

            //CashIn
            $this->listCashIn($browser);
            $this->createCashIn($browser);
            $this->editCashIn($browser);
//            $this->deleteCashIn($browser);
            $this->listCashIn($browser);
            $this->exportCashIn($browser);

            //CashOut
            $this->listCashOut($browser);
            $this->createCashOut($browser);
            $this->editCashOut($browser);
//            $this->deleteCashOut($browser);
            $this->listCashOut($browser);
            $this->exportCashOut($browser);

            // Shift Sales
            $browser->pause(3000);
            $this->listShift($browser);
            $this->listShiftSales($browser);
            $this->invoiceShiftSales($browser);
            $this->finalClosingShiftSales($browser);
            $this->exportExcelShiftSales($browser);

            // Shift Payment
            $browser->pause(3000);
            $this->listShift($browser);
            $this->listShiftPayment($browser);
//            $this->finalClosingShiftPayment($browser);
            $this->exportExcelShiftPayment($browser);
        });
    }

    //////////// Customer Start //////////////
    private function listCustomer(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(2) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(2) > a')
            ->waitFor('div.show-menu')
            ->pause(2000);
    }

    private function createCustomer($browser){
        $browser->pause(2000);
        $browser->click('div:nth-child(2) > div > button')->pause(2000);
        $browser->type('#input-course-id',"001122")->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-course-name','New Customer')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('#customer-closing-day')->pause(1000);
        $browser->click('#customer-closing-day > option:nth-child(4)')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->click('#customer-tax')->pause(1000);
//        $browser->click('#customer-tax > option:nth-child(2)')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-course-manager','123')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#postCode-first','123')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#postCode-second','4544')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-customer-address','address test create')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-customer-phone','01234567892')->pause(1000);
        $browser->type('#input-course-name','New Customerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-course-name','New Customer')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->assertSee('Create customer success')
            ->pause(4000);
    }
    private function editCustomer($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(4) > i')->pause(5000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-course-name','Customer Update')->pause(2000);
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
    //////////// Customer End //////////////

    //////////// Driver Start //////////////
    private function listDriver(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(2) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(1) > a')
            ->waitFor('div.show-menu')
            ->pause(2000);
    }

    private function createDriver($browser){
        $browser->pause(2000);
//        $browser->visit('/data-management/list-driver-create');
        $browser->click('div:nth-child(2) > div > button')->pause(2000);
        $browser->click('#select-type-driver > div:nth-child(1) > label')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-empolyee-number',"001122")->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-fullname','New Driver')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $getDate = Carbon::now()->format('Y-m-d');
        $this->mapDate($browser, '.input-group.mb-3 .input-group-append', $getDate);
        $browser->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-character','29E2-12362')->pause(1000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        //Validate
        $browser->type('#input-date-hire-date','2023/01-08')->pause(1000);
        $getDate = Carbon::now()->format('Y-m-d');
        $this->mapDate($browser, '.input-group.mb-3 .input-group-append', $getDate);
        $browser->pause(1000);
        $browser->type('#input-fullname','New Driverzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-fullname','New Driver')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000)
            ->waitFor('.zone-table')
//            ->assertSee('Create driver success')
            ->pause(4000);
    }
    private function editDriver($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(5) > i')->pause(5000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-fullname','Driver Edit')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')
//            ->assertSee('Update driver success')
            ->pause(4000);
    }

    private function deleteDriver($browser){
        $browser->pause(5000)
            ->click('tbody > tr:nth-child(5) > td:nth-child(6) > i')->pause(3000)
            ->click("footer > button.btn.btn-primary")
            ->waitFor('.toast-body')
            ->pause(4000);
//        $browser->click('.btn-save')->waitFor('.toast-body')
//            ->assertSee('Update driver success')->pause(4000);
    }
    //////////// Driver End //////////////


    //////////// Course Start //////////////
    private function listCourse(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(1) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(1) > ul > li:nth-child(2) > a')
            ->waitFor('.zone-table')
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
        // Ship date
        $getDate = Carbon::now()->format('Y-m-d');
        $this->mapDate($browser, '.input-group-append', $getDate);
        $browser->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
//        $browser->type('#input-course-name',"New Test Course")->pause(2000);
//        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('#input-driver-name')->pause(2000);
        $browser->click('#input-driver-name > option:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Vihicle number
        $browser->type('#input-vihicle-number',"1234")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Start Time
        $browser->click('div.row > div:nth-child(1) > div > div > div')->pause(2000);
        $browser->click('div:nth-child(1) > div > div > div > div > div > div:nth-child(1) > ul > li:nth-child(9)')->pause(2000);
        $browser->click('div:nth-child(1) > div > div > div > div > div > div:nth-child(2) > ul > li:nth-child(1) > div')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // End Time
        $browser->click('div:nth-child(1) > div.col-sm-12> div > div.row > div:nth-child(2) > div > div > div')->pause(2000);
        $browser->click('div:nth-child(2) > div > div > div > div > div > div:nth-child(1) > ul > li:nth-child(19)')->pause(2000);
        $browser->click('div:nth-child(2) > div > div > div > div > div > div:nth-child(2) > ul > li:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Break Time
        $browser->click('div.row > div:nth-child(3) > div > div > div')->pause(2000);
        $browser->click('div:nth-child(3) > div > div > div > div > div > div:nth-child(1) > ul > li:nth-child(16)')->pause(2000);
        $browser->click('div:nth-child(3) > div > div > div > div > div > div:nth-child(2) > ul > li:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Customer name
        $browser->click('#input-customer-name')->pause(2000);
        $browser->click('#input-customer-name > option:nth-child(1)')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Depature place
        $browser->type('#input-depature-place',"Dong Da")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Arrival place
        $browser->type('#input-arrival_place',"Kim Ma")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Item name
        $browser->type('#input-item-name',"Milk")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Quantity
        $browser->type('#input-quantity',"1")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Unit Price
        $browser->type('#input-unitPrice',"1000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Weight
        $browser->type('#input-unitPrice',"10")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Freight cost
        $browser->type('#input-freight-cost',"2000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Freight cost
        $browser->type('#input-freight-cost',"2000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // cooperating company payment amount
        $browser->type('#input-freight-cost',"0")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // cooperating company payment amount
        $browser->type('#input-freight-cost',"0")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Expressway/ferry fee
        $browser->type('#input-hight-way',"3000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Commission
        $browser->type('#input-expenses',"1000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        // Meal subsidy amount
        $browser->type('#input-bunus-amount',"1000")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->pause(10000);
    }
    private function editCourse($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(8) > i')->pause(4000)
            ->click(".btn-save")->pause(2000)
            ->type('#input-item-name',"Milk new")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')
            ->pause(4000);
    }

    private function deleteCourse($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(9) > i')->pause(3000)
            ->click("footer > button.btn.btn-primary")
            ->waitFor('.toast-body')->pause(4000);
    }
    //////////// Course End ///////////////

    //////////// Shift End ///////////////
    private function listShift($browser)
    {
        $browser
            ->mouseover('div.show-menu > ul > li:nth-child(1) > span')
            ->pause(2000);
        $browser->click('div.show-menu > ul > li:nth-child(1) > ul > li:nth-child(1) > a')->pause(2000);
        $browser->waitFor('.list-shift')->pause(4000);
    }

    private function editShift($browser)
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

    private function exportExcelShift($browser)
    {
        $browser->waitFor('div:nth-child(1) > .btn-excel')->pause(2000);
        $browser->click('div:nth-child(1) > .btn-excel')->pause(10000);
    }
    //////////// Shift End ///////////////

    //////////// Shift Hight Way Fee Start //////////////
    private function listHighWayFee(Browser $browser)
    {
        $browser->click('div:nth-child(1) > div > div > button:nth-child(2)')->pause(6000);
    }

    private function exportExcelHighWayFee($browser)
    {
        $browser->waitFor('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(2) > div')->pause(2000);
        $browser->click('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(2) > div')->pause(10000);
    }
    //////////// Shift Hight Way Fee End ///////////////

    //////////// Shift Meal Start //////////////
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
    //////////// Shift Meal End ///////////////

    //////////// Shift Sales Start //////////////
    private function listShiftSales(Browser $browser)
    {
        $browser->click('div:nth-child(1) > div > div > button:nth-child(4)')->pause(6000);
    }

    private function invoiceShiftSales($browser)
    {
        $browser->waitFor('tbody:nth-child(2) > tr:nth-child(1) > td.img-pdf')->pause(2000);
        $browser->click('tbody:nth-child(2) > tr:nth-child(1) > td.img-pdf')->pause(2000);
        $browser->waitFor('#modal-tax___BV_modal_body_ > div:nth-child(3) > button.btn.mr-2.btn-color-active-import.btn-secondary.rounded-pill')->pause(2000);
        $browser->click('#modal-tax___BV_modal_body_ > div:nth-child(3) > button.btn.mr-2.btn-color-active-import.btn-secondary.rounded-pill')->pause(10000);
    }

    private function finalClosingShiftSales($browser)
    {
        $browser->waitFor('div:nth-child(2) > .btn-temporary')->pause(2000);
        $browser->click('div:nth-child(2) > .btn-temporary')->pause(4000);
        $browser->click('div:nth-child(2) > .btn.btn-final')->pause(4000);
    }

    private function exportExcelShiftSales($browser)
    {
        $browser->waitFor('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(1) > div')->pause(2000);
        $browser->click('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(1) > div')->pause(10000);
    }
    //////////// Shift Sales End ///////////////

    //////////// Shift Payment Start //////////////
    private function listShiftPayment(Browser $browser)
    {
        $browser->click('div:nth-child(1) > div > div > button:nth-child(5)')->pause(6000);
    }

    private function finalClosingShiftPayment($browser)
    {
        $browser->waitFor('div:nth-child(2) > .btn-temporary')->pause(2000);
        $browser->click('div:nth-child(2) > .btn-temporary')->pause(4000);
        $browser->click('div:nth-child(2) > .btn.btn-final')->pause(4000);
    }

    private function exportExcelShiftPayment($browser)
    {
        $browser->waitFor('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(3) > div')->pause(2000);
        $browser->click('div.col-sm-12.col-md-4.col-lg-4.col-xl-4.col-12 > div:nth-child(3) > div')->pause(10000);
    }
    //////////// Shift Payment End ///////////////

    //////////// CashIn Start //////////////
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
        $browser->click('thead > tr > th.th-sort.th-id.th-course-id')->pause(2000);
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
    //////////// CashIn End ///////////////

    //////////// CashOut Start ///////////////
    private function listCashOut(Browser $browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(3) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(3) > ul > li:nth-child(2) > a')
            ->waitFor('div.show-menu')
            ->pause(3000);
    }

    private function exportCashOut(Browser $browser)
    {
        $browser->waitFor('.page-list-cashDisbursement')->pause(2000)
            ->click('.btn-excel')
            ->pause(8000);
    }

    private function createCashOut($browser){
        $browser->pause(2000);
//        $browser->visit('/data-management/list-driver-create');
        $browser->click('tbody > tr > td.text-center.td-control')->pause(6000);
        $browser->click('.btn-edit')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $getDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $this->mapDate($browser, 'div.input-group-append', $getDate);
        $browser->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-deposit-day',"2000")->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->click('#input-payment-method')->pause(1000);
        $browser->click('#input-payment-method > option:nth-child(1)')->pause(1000);
        $browser->type('#input-notes',"test cash out")->pause(2000);
        $browser->type('#input-payment-day',"$getDate"."zxczxc")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-payment-day',"$getDate")->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->pause(8000);
    }

    private function editCashOut($browser){
        $browser->click('tbody > tr:nth-child(1) > td.td-cash-edit.td-control > i')->pause(4000);
        $browser->type('#input-deposit-day',"1000")->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->pause(4000);
    }

    private function deleteCashOut($browser){
        $browser->pause(3000)
            ->click('tbody > tr:nth-child(1) > td.td-cash-delete.td-control > i')->pause(3000)
            ->click("div:nth-child(2) > .btn-color-active-import")
            ->waitFor('.toast-body')->pause(4000);
        $browser->pause(6000);
//        $browser->click('.btn-save')->waitFor('.toast-body')
//            ->assertSee('Update driver success')->pause(4000);
    }
    //////////// CashOut End ///////////////

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
