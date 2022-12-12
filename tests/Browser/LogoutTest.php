<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    /**
     * A scenario of automation test, try to login by super admin account
     * After login, application will visit all page to check the response working well
     * After visit all page by url, will move to page one by one by click.
     * Almost done, LogOut is the last step
     * @return void
     */

    public function testGeneral()
    {
        $this->browse(function ($browser) {
            $this->login($browser);
            $this->logoutSuccess($browser);
        });
    }

    public function logoutSuccess($browser)
    {
        $browser->visit('/shift-management/list-shift')->pause(5000)
            ->mouseover('.show-profile')->pause(5000)
            ->mouseover('.menu-profile')->pause(5000)->click('.menu-profile')->pause(5000)
            ->waitFor('.toast-header')->pause(5000)->assertSee('成功')
            ->assertSeeIn('.toast-header', '成功')->assertPathIs('/');
    }
}
