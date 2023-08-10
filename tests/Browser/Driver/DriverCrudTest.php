<?php

namespace Tests\Browser\Driver;

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

class DriverCrudTest extends DuskTestCase
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
            $this->listDriver($browser);
            $this->createDriver($browser);
            $this->editDriver($browser);
            $this->listDriver($browser);
            $this->deleteDriver($browser);
        });
    }

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
        $browser->type('#input-fullname','Bach Driver')->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $getDate = Carbon::now()->format('Y-m-d');
        $this->mapDate($browser, '.input-group.mb-3 .input-group-append', $getDate);
        $browser->pause(1000);
        $browser->click('.btn-save')->waitFor('.toast-body')->pause(1000);
        $browser->type('#input-character','29E2-12362')->pause(1000);
        //Validate
        $browser->type('#input-date-hire-date','2023/01-08')->pause(1000);
        $getDate = Carbon::now()->format('Y-m-d');
        $this->mapDate($browser, '.input-group.mb-3 .input-group-append', $getDate);
        $browser->pause(1000);

        $browser->click('.btn-save')->waitFor('.toast-body')
            ->assertSee('Create driver success')->pause(4000);

//        $browser->mouseover('.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2)')->pause(6000)
//            ->click('.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(3)')->pause(5000)
//            ->assertSee('ユーザー情報')->pause(2000)
//            ->assertSee('ユーザーID')->pause(2000)
//            ->assertSee('ユーザー名')->pause(2000)
//            ->assertSee('ユーザー権限')->pause(2000)
//            ->click('div.container > div > div.list-user__header > div.row > div:nth-child(2) > div > button')->pause(3000)
//            ->click('.btn-save')->pause(1000)
//            ->assertSee('入力または選択されていない項目があります。')
//            ->pause(1000)
//
//
//            ->type('#input-user-id','1122')->pause(2000)
//            ->type('#input-user-name','1122')->pause(2000)
//            ->click('#input-user-authority')->pause(2000)
//            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
//            ->type('#input-user-password','abc123456789')->pause(2000)
//            ->click('.btn-save')->pause(2000)
//            ->assertSee('指定のユーザーIDは既に使用されています。')->pause(1000) // diuplucate
//
//            ->type('#input-user-id','1124442')->pause(2000)
//            ->type('#input-user-name','1122')->pause(2000)
//            ->click('#input-user-authority')->pause(2000)
//            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
//            ->type('#input-user-password','abc123456789')->pause(2000)
//            ->click('.btn-save')->pause(1000)
//            ->assertSee('ユーザーIDは半角数字4桁以内で入力してください。')->pause(1000) //more 4 char
//
//            ->type('#input-user-id','4444')->pause(2000)
//            ->type('#input-user-name','Ten duoc phep vuot qua 20 ki tu')->pause(2000)
//            ->click('#input-user-authority')->pause(2000)
//            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
//            ->type('#input-user-password','abc123456789')->pause(2000)
//            ->click('.btn-save')->pause(2000)
//            ->assertSee('20文字以下にしてください。')->pause(1000) // name 20
//
//            ->type('#input-user-id','4444')->pause(2000)
//            ->type('#input-user-name','Linhnt')->pause(2000)
//            ->click('#input-user-authority')->pause(2000)
//            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
//            ->type('#input-user-password','abc123456789999999999999')->pause(2000)
//            ->click('.btn-save')->pause(1000)
//            ->assertSee('パスワードは８文字以上16文字以内半角英数字で入力してください')->pause(1000) // pass 20
//
//            ->type('#input-user-id','4444')->pause(2000)
//            ->type('#input-user-name','Linhnt')->pause(2000)
//            ->click('#input-user-authority')->pause(2000)
//            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
//            ->type('#input-user-password','abc12345678')->pause(2000)
//            ->click('.btn-save')->pause(4000)
//            ->assertSee('4444')->pause(5000); // name 20

    }
    private function editDriver($browser){
        $browser->click('tbody > tr:nth-child(5) > td:nth-child(5) > i')->pause(5000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-fullname','Bach Driver Edit')->pause(2000);
        $browser->click('.btn-save')->waitFor('.toast-body')
            ->assertSee('Update driver success')->pause(4000);
    }

    private function deleteDriver($browser){
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
