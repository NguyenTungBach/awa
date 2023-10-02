<?php

namespace Tests\Browser\St;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\DriverCourse;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;

class SystemCaseBTest extends DuskTestCase
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

//        $user = User::where('user_code','1111')->first();

        $now = Carbon::now()->firstOfMonth()->format('Y-m-d');

        Course::factory()->create([
            'customer_id' => 1,
            'driver_id' => 1,
            'vehicle_number' => '12320',
            'course_name' => 'Course name driver',
            'ship_date' => $now,
            'start_date' => '09:00',
            'end_date' => '10:00',
            'break_time' => '00:00',
            'departure_place' => 'Departure place 0',
            'arrival_place' => 'Arrival place 0 ',
            'item_name' => 'Item name 0',
            'quantity' => 1,
            'price' => '1000',
            'weight' => '100',
            'ship_fee' => '5000',
            'associate_company_fee' => '2000',
            'expressway_fee' => '2000',
            'commission' => '2000',
            'meal_fee' => '2000',
            'note' => NULL,
        ]);
        Artisan::call('db:seed --class=DriverCourseSeeder');

        $this->browse(function ($browser) {
            $browser->maximize();
            $this->loginDriverCaseBGeneral($browser);

            // Shift
            $browser->pause(3000);
            $this->listShift($browser);
            $this->listShiftBack($browser);
            $this->listShiftNext($browser);
            $this->listShiftNext($browser);
            $this->listShiftBack($browser);
            $this->logOutCaseB($browser);
        });
    }

    //////////// Shift End ///////////////
    private function listShift($browser)
    {
        $browser
            ->mouseover('div.show-menu > ul > li:nth-child(1) > span')
            ->pause(2000);
        $browser->click('div.show-menu > ul > li:nth-child(1) > ul > li:nth-child(1) > a')->pause(2000);
        $browser->waitFor('.list-shift')->pause(4000);
    }
    private function listShiftBack($browser)
    {
        $browser->click('div.picker-month-year__back')->pause(2000);
        $browser->waitFor('.list-shift')->pause(4000);
    }
    private function listShiftNext($browser)
    {
        $browser->click('div.picker-month-year__next')->pause(2000);
        $browser->waitFor('.list-shift')->pause(4000);
    }
    //////////// Shift End ///////////////

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

    public function logOutCaseB($browser) {
        $browser->mouseover('.icon-dropdown')->pause(2000);
        $browser->press('div.show-profile > ul')->pause(5000);
    }
}
