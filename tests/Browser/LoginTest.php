<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
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
        });

    }
}
