<?php

namespace Tests\Browser\tests\Browser\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;

class PerformanceTest extends DuskTestCase
{
    use withFaker;
    private $currentDate;

    public function testPerformance()
    {
        $this->browse(function ($browser) {
            $this->loginAdmin($browser);
            $browser->pause(4000);
            $browser->assertSee('シフト表');

            $object = 'shift';

            $this->list($browser, $object);

            $browser->pause(5000);

            $browser->press('.list-shift__control .btn-group button:nth-child(3)')->pause(4000);

            $this->seeList($browser, [
                '実務実績表',
                'Crew番号', 'Crew区分', 'Crew名', '勤務日数',
                '休日数',
                '有給休暇数',
                '希望休数',
                '拘束時間',
                '実質乗車時間',
                '休憩時間',
                '残業時間',
                'ポイント'
            ]);
            $browser
                    ->mouseover('.picker-month-year__back')->pause(500)->press('.picker-month-year__back')->pause(3000);


            $browser
                    ->mouseover('.picker-month-year__next')->pause(500)->press('.picker-month-year__next')->pause(3000);


            $this->sortFields($browser, 4);

            $browser->pause(3000);

            $browser->press('.btn-excel');

            $browser->pause(3000);

            $object = 'driver';

            $this->list($browser, $object);

            $this->goToCreate($browser, $object);

            $this->goToPerformance($browser, 3);

            $object = 'driver';

            $this->list($browser, $object);

            $this->goToEdit($browser, $object);

            $this->goToPerformance($browser, 3);

            $object = 'driver';

            $this->list($browser, $object);

            $this->goToDelete($browser, $object);

            $this->goToPerformance($browser, 3);

        });
    }

    private function goToPerformance($browser, $tab = 2)
    {
        $object = 'shift';

        $this->list($browser, $object, 'month');

        $browser->press(".list-shift__control .btn-group button:nth-child($tab)")->pause(3000);

        $this->scrollTo($browser, "table:nth-child($tab) tr:last-child td:nth-child(1)");
        $browser->pause(2000);
        $this->scrollTo($browser, "table:nth-child($tab) tr:first-child");
    }

    private function goToCreate($browser, $object)
    {
        switch ($object) {
            case 'driver':
                $listAssertSee = ['Crew新規登録'];
                break;
            default:
                dd('error');
                break;
        }
        $btnCreateNew = '.btn-color-sign-up';
        if ($object == 'driver') {
            $btnCreateNew = '.zone-btn-sign-up .btn-control';
        }
        $btnSave = '.btn-save';
        $browser
            ->pause(1000)
            ->press($btnCreateNew)
            ->pause(3000);

        $this->seeList($browser, $listAssertSee);

        $obj = $this->fillForm($browser, $object);

        $browser->press($btnSave)->pause(3000);

        $this->scrollTo($browser);

        $this->seeList($browser, $obj);
    }

    private function goToViewDetail($browser, $object)
    {
        switch ($object) {
            case 'driver':
                $td = 4;
                $listAssertSee = ['Crew詳細', '基本情報', 'Crew番号', 'Crew名'];
                break;
            default:
                dd('error');
                break;
        }

        $this->scrollTo($browser);

        $browser
            ->press("tbody > tr:last-child > td:nth-child($td)")
            ->pause(1000);

        $this->seeList($browser, $listAssertSee);

        $browser->pause(1000);

        return $listAssertSee;
    }

    private function goToEdit($browser, $object)
    {
        $browser
            ->pause(1000);
        $sees = [];
        if (in_array($object, ['user', 'course', 'driver'])) {
            $sees = $this->goToViewDetail($browser, $object);
        }

        switch ($object) {
            case 'driver':
                $listAssertSee = $sees;
                break;
            default:
                dd('error on Edit', $object);
                break;
        }
        $btnSave = '.btn-save';

        $this->pressEditButton($browser);

        $this->seeList($browser, $listAssertSee);

        $browser->pause(1000);

        $obj = $this->fillForm($browser, $object, true);

        $browser
            ->press($btnSave)
            ->pause(4000);

        $this->seeList($browser, $obj);

        $browser->pause(2000);

        // if ($object == 'driver') {
        //     $this->updateCoursesForDricer($browser, $object);
        // }

        $this->pressBack($browser);

    }

    private function goToDelete($browser, $object)
    {
        $browser->pause(2000);

        $deleteRow = 'tbody > tr:last-child';
        $this->scrollTo($browser);
        $codeRemoved = $browser->text($deleteRow . ' > td:nth-child(1)');
        if ($codeRemoved) {
            $browser
                ->press($deleteRow . ' > td:last-child')
                ->waitFor('#modal-delete')
                ->assertSee('確認')
                ->assertSee($object == 'driver'? '削除しますか？': '本当に削除しますか？')
                ->pause(2000)
                ->press('#modal-delete .btn-primary')
                ->pause(3000)
            ;
            $browser->assertDontSee($codeRemoved);
        }
    }

    private function fillForm($browser, $object, $edit = false)
    {
        switch ($object) {
            case 'driver':
                return $this->fillFormDriver($browser, $edit);
                break;
            default:
                dd('not found case');
                break;
        }
    }

    private function fillFormDriver($browser, $edit)
    {
        if (!$edit) {
            // $this->assertNotNull($browser->element('input[name="select-type-driver"]'));
            $browser->press('.custom-radio:nth-child(2) label');

            // set driver code
            $browser->typeSlowly('#input-empolyee-number', $this->faker->randomNumber(4));

            // set fullname
            $browser->typeSlowly('#input-fullname', $this->faker->firstName . ' ' . $this->faker->lastName);

            // set start date
            $browser->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('+7 days', '+10 days')->format('Y-m-d'));

            // set date of birth
            $browser->typeSlowly('#input-date-date-of-birth', $this->faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'));

            // set date of birth
            $browser->typeSlowly('#input-grade', $this->faker->numberBetween(1000, 10000));


            // set available day
            $browser->select('#input-available-days', 5)->pause(1000);
            // set holiday
            $browser
                ->check('.custom-checkbox:nth-child(1) label')
                ->pause(1000)
                ->check('.custom-checkbox:nth-child(2) label');

            // set note
            $browser
                ->pause(1000)
                ->scrollIntoView('#input-notes')
                ->pause(2000)
                ->typeSlowly('#input-notes', $this->faker->text(50))
                ->pause(1000);
        } else {

            $browser->press('.custom-radio:nth-child(1) label');

            // set fullname
            $browser->typeSlowly('#input-fullname', $this->faker->firstName . ' ' . $this->faker->lastName);


            // set start date
            $browser->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('+11 days', '+15 days')->format('Y-m-d'));

            // set date of birth
            $browser->typeSlowly('#input-date-date-of-birth', $this->faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'));

            $browser->typeSlowly('#input-grade', $this->faker->numberBetween(1000, 10000));

            // set available day
            $browser->select('#input-available-days', 3)->pause(1000);
            // set holiday
            $browser
                ->check('.custom-checkbox:nth-child(2) label')
                ->pause(1000)
                ->check('.custom-checkbox:nth-child(5) label')
                ->pause(1000)
                ->check('.custom-checkbox:nth-child(6) label')
                ->pause(1000)
                ->check('.custom-checkbox:nth-child(7) label');

            $browser
                ->scrollIntoView('#input-retirement-date')
                ->typeSlowly('#input-retirement-date', $this->faker->dateTimeBetween('+20 days', '+30 days')->format('Y-m-d'));
        }

        $browser
            ->scrollIntoView('.btn-save')
            ->pause(1000);

        return $this->dataSee = [
            'code' => $browser->value('#input-empolyee-number'),
            'name' => $browser->value('#input-fullname')
        ];
    }

    private function sortFields($browser, $tblIndex = 1)
    {
        $browser->pause(2000);
        $browser
            ->press("table:nth-child($tblIndex) .driver-code")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .driver-code")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .driver-flag")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .driver-flag")
            ->pause(4000);
    }

    private function compairMonthTab($browser)
    {
        $result = $this->getDataRow($browser);
        $this->pressBack($browser);
        $browser->pause(5000);
        $browser->press('.list-shift__control .btn-group button:nth-child(2)')->pause(4000);
        $this->seeList($browser, $result);
    }

    private function getDataRow($browser, $viewTime = 'month', $fromCol = 1,  $rowIndex = 1)
    {
        $row = "tr:nth-child($rowIndex)";

        if (!$this->currentDate || !strtotime($this->currentDate)) {
            $this->currentDate = date('Y-m-d');
        }
        if ($viewTime == 'month') {
            $startDate = \Carbon\Carbon::parse($this->currentDate)->startOfMonth();
            $endCol = $startDate->daysInMonth;
        } else {
            $startDate = \Carbon\Carbon::createFromDate(date('Y', strtotime($this->currentDate)), date('m', strtotime($this->currentDate)), 21)->subMonth();
            $endCol = $startDate->daysInMonth;
            if ($fromCol == 1) {
                $endCol = 20;
            }
        }

        $result = [
                    "total_time" => "0.00",
                    "driving_time" => "0.00",
                    "over_time" => "0.00",
                    "working_days" => "0",
                    "days_off" => "0",
                    "paid_holidays" => "0",
                    "max_total_time_day" => "0.00",
                    "max_driving_time_day" => "0.00",
                    "working_over_day" => "0"
                ];
        $totalTime = [];
        $drivingTime = [];
        $breakTime = [];
        $browser->waitFor('table');
        for ($i = $fromCol; $i <= $endCol; $i ++) {
            $col = 3 + $i;
            $column = $row . " td:nth-child($col)";
            if ($browser->element($column)) {
                $hasCode = [];
                if ($browser->element($column . " > .show-node > div > .show-course:nth-child(1)")) {
                    for($c = 1; $c <= 3; $c ++) {
                        if ($browser->element($column . " > .show-node > div > .show-course:nth-child($c)")) {
                            $hasCode[] = $browser->text($column . " > .show-node > div > .show-course:nth-child($c)");
                        }
                    }

                    $browser->press($column)->waitFor('#modal-detail')->pause(1000);

                    $this->seeList($browser, $hasCode);
                    $drivingTime[$i] = 0;
                    $totalTime[$i] = 0;
                    $breakTime[$i] = 0;
                    if ($hasCode[0] != '待機' && $hasCode[0] != '社内業務') {
                        for($c = 1; $c <= count($hasCode) + 1; $c ++) {
                            $element = "#modal-detail .detail-node .item-node:nth-child($c)";
                            if ($browser->element($element)) {
                                $title = "$element .type-node";
                                $times = $this->getTimeOnModal($browser, $element);
                                if ($times) {
                                    $totalTime[$i] += $times['hours'];
                                    $breakTime[$i] += $times['breakTime'];
                                    if ($browser->element($title) && in_array($browser->text($title), $hasCode)) {
                                        $drivingTime[$i] += $times['hours'];
                                    }
                                } else {
                                    dd("times $c on modal is empty");
                                }
                            }
                        }
                    } else {
                        $totalTime[$i] = 9;
                        $breakTime[$i] = 1;
                    }

                    $result['working_days'] ++;

                    $browser->press('#modal-detail button:nth-child(1)')->pause(1000);

                } else {
                    if ($browser->element($column . " > .show-node > div") && in_array($browser->text($column . " > .show-node > div"), ['公休', '固定休', '希望休', '有給休暇'])) {
                        $result['days_off'] ++;
                        if ($browser->text($column . " > .show-node > div") == '有給休暇') {
                            $result['paid_holidays'] ++;
                        }
                    }
                }
            }
        }
        $result['total_time'] += !empty($totalTime) ? array_sum($totalTime): 0;
        $result['driving_time'] += !empty($drivingTime) ? array_sum($drivingTime): 0;
        $result['over_time'] = $result['total_time'] - ($breakTime? array_sum($breakTime): 0) - ($result['working_days'] * 8);
        $result['max_total_time_day'] = !empty($totalTime) ? max($totalTime): 0;
        $result['max_driving_time_day'] = !empty($drivingTime) ? max($drivingTime): 0;
        foreach ($totalTime as $hour) {
            if ($hour > 15) {
                $result['working_over_day'] ++;
            }
        }

        return $result;

    }

    private function getTimeOnModal($browser, $element)
    {
        $result = [];
        for($i = 1; $i <= 3; $i ++) {
            $span = "$element div:nth-of-type($i) span";
            if (!$browser->element($span)) {
                dd("not found $span");
            }

            $arr = $browser->element($span) ? explode(': ', $browser->text($span)) : [];
            if (count($arr) == 2) {
                $result[$i] = $arr[1];
            }
        }

        if (count($result) == 3) {
            $interval = (strtotime($result[2]) - strtotime($result[1])) / 3600;

            $result = [
                'hours' => $interval,
                'breakTime' => $this->hoursToDecimal($result[3])
            ];
        }
        return $result;
    }

    private function hoursToDecimal($timeInHours) {
        $times = explode(':', $timeInHours);
        return $times[0] + ($times[1]/60);
    }

    private function loginAdmin($browser, $permission = 'admin')
    {
        $userName = '1122';
        $passWord = 'abc12345678';
        if ($permission != 'admin') {
            $userName = '2233';
            $passWord = 'abc123456789';
        }

        $url = $browser->driver->getCommandExecutor()->getAddressOfRemoteServer();
        $uri = '/session/' . $browser->driver->getSessionID() . '/chromium/send_command';
        $downloadPath = storage_path('app\public\downloads');

        $body = [
            'cmd' => 'Page.setDownloadBehavior',
            'params' => ['behavior' => 'allow', 'downloadPath' => $downloadPath]
        ];

        // (new \GuzzleHttp\Client())->post($url . $uri, ['body' => json_encode($body)]);

        $browser->visit('/login')
            ->pause(5500)
            ->clear('#user_id')
            ->clear('#password')
            ->typeSlowly("#user_id", $userName)
            ->typeSlowly('#password', $passWord)
            ->press(".login-btn")->pause(1000);

    }

    private function list($browser, $object, $shiftView = 'week')
    {
        switch ($object) {
            case 'driver':
                $item1 = 2;
                $item2 = 1;
                $listAssertSee = [ 'Crew情報', 'Crew番号', 'Crew名', '在籍状況', '詳細', '削除'];
                break;
            case 'shift':
                $item1 = 1;
                $item2 = 1;
                $listAssertSee = ['シフト表', 'Crew番号', 'Crew区分', 'Crew名'];
                break;
            default:
                dd('error');
                break;
        }
        $element = "ul.item-modules li:nth-child($item1) ul.item-path li:nth-child($item2)";
        $hover = "ul.item-modules > li:nth-child($item1)";
        $browser
            ->pause(1000)
            ->mouseover($hover);

        $browser
            ->waitFor($element)
            ->click($element)
            ->pause(3000);

        $this->mouseOut($browser);

        $this->seeList($browser, $listAssertSee);

        if ($object == 'shift') {
            if (!$this->currentDate && $browser->element('.picker-month-year__time')) {
                $browser
                    ->mouseover('.picker-month-year__next')->pause(500)->press('.picker-month-year__next')->pause(3000);

                $this->currentDate = str_replace([' ', '年', '月', '|'], ['', '', '', '-'], $browser->text('.picker-month-year__time'));
            }

            $browser->pause(2000);

            $browser->press('.list-shift__control .btn-group button:nth-child(1)')->pause(4000);

            $this->mouseOut($browser);

            // if ($shiftView != 'week') {
            //     $browser->press('.zone-select-week-month button:nth-child(2)')->pause(4000);
            // }
        }
    }

    private function setCurrentMonth($browser) {
        if ($browser->element('.picker-month-year__time')) {
            $browser
                ->mouseover('.picker-month-year__next')->pause(500)->press('.picker-month-year__next')->pause(3000);
            $this->currentDate = str_replace([' ', '年', '月', '|'], ['', '', '', '-'], $browser->text('.picker-month-year__time'));
        }
    }

    private function mouseOut($browser)
    {
        $browser->waitFor('.app-layout')->mouseover('.app-layout');
    }

    private function seeList($browser, $list = [])
    {
        if ($list && is_array($list) && !empty($list)) {
            foreach ($list as $k => $see) {
                if ($k == 'element' || $k == 'textInElement' || $k == 'day') {
                    continue;
                }
                $browser->assertSee($see);
            }
            if (isset($list['element']) && isset($list['textInElement']) && isset($list['day']) && $list['day'] > 0) {
                $this->scrollTo($browser, $list['element']);
                $this->scrollTo($browser, 'tr:first-child');
                $browser->assertSeeIn($list['element'], $list['textInElement']);
            }
        }
    }

    private function scrollTo($browser, $element = 'tr:last-child td:last-child')
    {
        if ($browser->element($element)) {
            $browser
                ->pause(1000)
                ->scrollIntoView($element)
                ->pause(4000);
        } else {
            dd("not found [$element] on scroll");
        }

    }

    private function pressEditButton($browser, $nb = 1)
    {

        if ($browser->element(".zone-control:nth-child($nb)")) {
            $btnEdit = $browser->element(".zone-control:nth-child($nb) .btn-edit") ? ".zone-control:nth-child($nb) .btn-edit": '';
        } else {
            $btnEdit = $browser->element(".btn-edit") ? ".btn-edit": '';
        }
        if ($btnEdit)
        {
            $browser
                ->pause(1000)
                ->assertSee('編集')
                ->press($btnEdit)
                ->pause(3000);
        }
    }

    private function pressBack($browser, $nb = 1)
    {
        if ($browser->element(".zone-control:nth-child($nb)")) {
            $btnBack = $browser->element(".zone-control:nth-child($nb) .btn-return") ? ".zone-control:nth-child($nb) .btn-return": '';
        } else {
            $btnBack = $browser->element(".btn-return") ? ".btn-return": '';
        }

        if ($btnBack) {
            $browser
            ->pause(1000)
            ->press($btnBack);
        }

    }
}
