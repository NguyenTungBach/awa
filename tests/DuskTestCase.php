<?php

namespace Tests;

use App\Models\User;
use Carbon\Carbon;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreateDuskTestApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (!static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
            '--start-maximized',
        ])->all());

//        $options = (new ChromeOptions)->addArguments(collect([
//            '--window-size=1920,1080',
//        ])->unless($this->hasHeadlessDisabled(), function ($items) {
//            return $items->merge([
//                '--disable-gpu',
//                '--headless',
//            ]);
//        })->all());

        $options->setExperimentalOption('prefs', [
            'download.default_directory' => Storage::disk('local')->path('test_download')
        ]);

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    public function login($browser)
    {
//        $getYearNow = Carbon::now()->format("Y");
//        $this->get("/api/calendar/setup-data?targetyyyy=$getYearNow"); // Gọi API bằng phương thức GET
        $browser->visit('/')->waitFor('.login-btn')
            ->type('#user_id', '1122')->pause(1000)
            ->type('#password', 'abc12345678')->pause(1000)
            ->press('.login-btn')
            ->waitFor('.toast-body')->assertSee('Login success')
            ->assertSeeIn('.toast-body', 'Login success')
            ->pause(5000)
            ->waitFor('.zone-table')
            ->assertPathIs('/shift-management/list-shift')
            ->waitFor('div.show-menu > ul > li:nth-child(2) > span');
    }

    public function loginFaild($browser, $account, $password, $shouldSee = null) {
        $url = '/login';
        $browser->visit($url)->maximize()
            ->type('#user_id', $account)
            ->type('#password', $password)->releaseMouse()->press('.login-btn')
            ->pause(500)->assertSee($shouldSee)->pause(1000);
    }

    public function loginAdminGeneral(Browser $browser)
    {
        $browser->visit('/')->waitFor('.login-btn')
            ->type('#user_id', '1122')->pause(1000)
            ->type('#password', 'abc12345678')->pause(1000)
            ->press('.login-btn')
            ->waitFor('.toast-body')->assertSee('Login success')
            ->assertSeeIn('.toast-body', 'Login success')
            ->pause(5000)
            ->waitFor('.zone-table')
            ->assertPathIs('/shift-management/list-shift')
            ->waitFor('div.show-menu > ul > li:nth-child(2) > span');
    }

    public function logOut($browser) {
        $browser->releaseMouse()->press('.btn-logout')->pause(2000)->assertSee('ユーザID')->pause(1000);
    }

    public function visitPage($browser, $url, $hind) {
        $url = "/#" . $url;
        $browser->visit($url)->maximize()->assertSee($hind)->pause(1000);
    }

    public function visitByClick($browser, $press, $waitFor = null, $see = null, $pause = 1000) {
        $browser->releaseMouse()->press($press)->pause($pause)->waitFor($waitFor)->assertSee($see)->pause($pause);
    }

    public function typeTextBox($browser, $typeTo, $text = "") {
        $browser->assertSee($typeTo, 0.2)->type($typeTo, $text)->releaseMouse();
    }
}
