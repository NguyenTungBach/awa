<?php

namespace Database\Seeders;

use App\Imports\ResultAIImport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ResultAISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Excel::import(new ResultAIImport, 'output/workshift_result.csv');
    }
}
