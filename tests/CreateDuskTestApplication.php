<?php


namespace Tests;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

trait CreateDuskTestApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        Artisan::call('migrate:fresh --seed');
        $getYearNow = Carbon::now()->format("Y");
        $client = new Client();
        $apiUrl = url("/api/calendar/setup-data?targetyyyy=$getYearNow");
        $response = $client->get($apiUrl); // Gọi API bằng phương thức GET
//        $apiResponse = $response->getBody()->getContents();
        return $app;
    }
}
