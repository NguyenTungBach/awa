<?php

namespace Database\Seeders;

use App\Models\GroupChat;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $list = [
//            1 => '本社',
//            2 => '横浜第一',
//            3 => '平塚',
//            4 => '横浜第二',
//            5 => '静岡',
//            6 => '千葉',
//            7 => '東京',
//            8 => '八千代',
//            9 => '古河',
//            11 => '武蔵野',
////            12 => '埼玉',
//            13 => '所沢',
//            14 => '新潟',
//            15 => '名古屋',
//            16 => '安城',
//            17 => '浜松',
//            18 => '富山',
//            19 => '大阪',
//            20 => '神戸',
//            22 => '横浜第三',
//        ];
//        Department::whereNotIn('id', array_keys($list))->delete();
//        foreach ($list as $key => $name) {
//            $department = Department::where('id', $key)->first();
//            $checkGr = GroupChat::where('name', $name)->first();
//            if (!$department) {
//                Department::create([
//                    'id' => $key,
//                    'name' => $name
//                ]);
//            } else {
//                $department->id = $key;
//                $department->name = $name;
//                $department->save();
//            }
//            if (!$checkGr) {
//                GroupChat::create([
//                    'name' => $name
//                ]);
//            }
//        }
    }
}


