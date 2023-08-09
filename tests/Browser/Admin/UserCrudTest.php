<?php

namespace Tests\Browser\Admin;

use App\Models\Driver;
use App\Models\User;
use Facebook\WebDriver\WebDriverBy;
use Helper\Common;
use Illuminate\Support\Facades\Artisan;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class UserCrudTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
//        User::where('user_code','4444')->delete();
        Artisan::call('migrate:fresh --seed');

        $this->browse(function ($browser) {
            $browser->maximize();
            $browser->pause(5000);
            $this->loginAdminGeneral($browser);
            $this->listUser($browser);
            $this->createUser($browser);
            $this->editUser($browser);
            $this->listUser($browser);
            $this->deleteUser($browser);
        });
    }

    private function listUser($browser)
    {
        $browser->pause(2000)
            ->mouseover('div.show-menu > ul > li:nth-child(2) > span')
            ->pause(2000)
            ->click('div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(3) > a')
            ->pause(2000);
    }

    private function createUser($browser){
        $browser->pause(3000);
        $browser->click('div:nth-child(2) > div > button')->pause(2000);
        $browser->type('#input-user-id',"001122")->pause(1000);
        $browser->type('#input-user-name','Bach Admin')->pause(1000);
        $browser->click('#input-user-authority')->pause(1000);
        $browser->click('#input-user-authority > option:nth-child(3)')->pause(1000);
        $browser->type('#input-user-password',"abc12345678")->pause(1000);
        $browser->click('.btn-save')
            ->waitFor('.toast-body')
            ->assertSee('Create user success')
            ->pause(4000);

    }

    private function editUser($browser){
        $browser->click('tbody > tr:nth-child(1) > td:nth-child(4) > i')->pause(2000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-user-name','Bach Admin Update')->pause(2000)
            ->click('.btn-save')->pause(2000)
            ->waitFor('.toast-body')
            ->assertSee('Update user success')
            ->pause(5000);
    }

    private function deleteUser($browser){
        $browser
            ->click('tbody > tr:nth-child(1) > td:nth-child(5) > i')->pause(3000)
            ->click("footer > button.btn.btn-primary")
            ->waitFor('.toast-body')->pause(4000);
//        $browser->click('.btn-save')->waitFor('.toast-body')
//            ->assertSee('Update driver success')->pause(4000);
    }
}
