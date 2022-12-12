<?php

namespace Tests\Browser\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CourseTab extends DuskTestCase
{
    use withFaker;
    private $currentDate;
    private $resultToCheck;

    public function testCourseTab()
    {
        $this->browse(function ($browser) {
            $this->loginAdmin($browser);
            $browser->pause(4000);
            $browser->assertSee('シフト表');

            //get date from header
            $this->setCurrentDate($browser);

            // go to shift list
            $this->list($browser);

            $browser->pause(5000);

            // go to course tab to see list courses
            $this->goShiftTab($browser, 2);

            $this->seeList($browser, [
                'シフト表',
                'コース ID', 'グループ', 'コース名'
            ]);

            // press go to previous month
            $browser
                    ->mouseover('.picker-month-year__back')->pause(500)->press('.picker-month-year__back')->pause(3000);

            // press go to next month
            $browser
                    ->mouseover('.picker-month-year__next')->pause(500)->press('.picker-month-year__next')->pause(3000);

            // sort fields
            $this->sortFields($browser, 2);

            $browser->pause(3000);

            // go to edit course tab
            $object = 'course-tab';

            $this->goToEdit($browser, $object);

            //back tab default to check date edited
            $this->goShiftTab($browser);

            if ($this->resultToCheck && $this->resultToCheck['name'] && $this->resultToCheck['textInElement']) {
                $table = 'table:nth-child(1)';
                $trs = $browser->pause(5000)->elements( $table . " tbody tr");
                foreach ($trs as $k => $tr) {
                    $row = $k + 1;
                    $nameRow = $table . " tbody tr:nth-child($row) td:nth-child(3)";

                    if ($browser->element($nameRow)) {
                        $browser->scrollIntoView($nameRow);

                        if ($browser->text($nameRow) != $this->resultToCheck['textInElement']) {
                            continue;
                        }
                        $colElement =  $table . " tbody tr:nth-child($row)" . $this->resultToCheck['day'];
                        $browser->pause(5000);
                        $browser->assertSeeIn($colElement,  $this->resultToCheck['name']);
                        break;
                    } else {
                        dd("not found {$nameRow}");
                    }
                }
            }

            $browser->pause(3000);

            // go to edit again one code has been run by an other Crew
            $this->goShiftTab($browser, 2);

            $this->goToEdit($browser, $object);

            // back to shift first tab to check Crew just changed
            $this->goShiftTab($browser);

            if ($this->resultToCheck && $this->resultToCheck['name'] && $this->resultToCheck['textInElement']) {
                $table = 'table:nth-child(1)';
                $trs = $browser->pause(5000)->elements( $table . " tbody tr");
                foreach ($trs as $k => $tr) {
                    $row = $k + 1;
                    $nameRow = $table . " tbody tr:nth-child($row) td:nth-child(3)";

                    if ($browser->element($nameRow)) {
                        $browser->scrollIntoView($nameRow);

                        if ($browser->text($nameRow) != $this->resultToCheck['textInElement']) {
                            continue;
                        }
                        $colElement =  $table . " tbody tr:nth-child($row)" . $this->resultToCheck['day'];
                        $browser->pause(5000);
                        $browser->assertSeeIn($colElement,  $this->resultToCheck['name']);
                        break;
                    } else {
                        dd("Fail: not found {$nameRow}");
                    }
                }
            }

            // go to edit again one code has been run by an other Crew
            $this->goShiftTab($browser, 2);

            $this->pressEditButton($browser);

            $rowNumber = 1;
            $rowElement = "tbody tr:nth-child($rowNumber)";
            if ($browser->element($rowElement)) {
                $code = '';
                $name = '';
                if ($browser->element($rowElement . " td:nth-child(1)")) {
                    $code = $browser->text($rowElement . " td:nth-child(1)");
                }
                if ($browser->element($rowElement . " td:nth-child(3)")) {
                    $name = $browser->text($rowElement . " td:nth-child(3)");
                }
                $daysInMonth = Carbon::parse($this->currentDate)->daysInMonth;
                $colEdited = '';
                $day = '';
                // search empty cell to edit
                for ($col = 4; $col <= $daysInMonth + 3; $col ++) {
                    $day = " td:nth-child($col)";
                    $column = $rowElement . $day;
                    if ($browser->element($column)) {
                        $firstDiv = $column . " .node-course-base";
                        if ($browser->element($firstDiv)) {
                            if ($browser->text($firstDiv) != '-') {
                                $colEdited = $column;
                                break;
                            }
                        }
                    }
                }
                if ($colEdited) {
                    $browser->press($colEdited);

                    $wrapCheckbox = "#select-day-off";

                    $browser->waitFor($wrapCheckbox);

                    if ($browser->elements($wrapCheckbox . " .custom-radio")) {
                        $checkboxes = $browser->elements($wrapCheckbox . " .custom-radio");
                        $checkboxSelected = 1;

                        $checkboxElement = $wrapCheckbox . " .custom-radio:nth-child($checkboxSelected) label";
                        $spanElement = $checkboxElement ." span";
                        if ($browser->element($spanElement)) {
                            $driverName = $browser->text($spanElement);
                        }

                        $browser->pause(3000)->scrollIntoView($checkboxElement)->press($checkboxElement);
                        $browser->pause(3000)->scrollIntoView('.zone-save .btn-save')->press('.zone-save .btn-save');

                        $this->resultToCheck = [
                            'code' => $code,
                            'name' => $name,
                            'element' => 'table:nth-child(2) ' . $colEdited . " .node-course-base",
                            'textInElement' => $driverName ?? '-',
                            'day' => $day
                        ];
                        $browser->press('.list-shift__control .btn-save')->pause(4000);

                        $this->seeList($browser, $this->resultToCheck);
                    }
                }
            }

            // back to shift first tab to check Crew just changed
            $this->goShiftTab($browser);
            if ($this->resultToCheck && $this->resultToCheck['name'] && $this->resultToCheck['textInElement']) {
                $table = 'table:nth-child(1)';
                $trs = $browser->pause(5000)->elements( $table . " tbody tr");
                foreach ($trs as $k => $tr) {
                    $row = $k + 1;
                    $nameRow = $table . " tbody tr:nth-child($row) td:nth-child(3)";

                    if ($browser->element($nameRow)) {
                        $browser->scrollIntoView($nameRow);
                        $browser->pause(500);
                        if ($browser->text($nameRow) != $this->resultToCheck['textInElement']) {
                            continue;
                        }
                        $colElement =  $table . " tbody tr:nth-child($row)" . $this->resultToCheck['day'];
                        $browser->pause(5000);
                        $browser->assertSeeIn($colElement,  '');
                        break;
                    } else {
                        dd("Fail: not found {$nameRow}");
                    }
                }
            }
        });
    }

    private function goShiftTab($browser, $tab = 1)
    {
        $browser->press(".list-shift__control .btn-group button:nth-child($tab)")->pause(4000);
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
            case 'course-tab':
                $listAssertSee = [
                    'シフト表',
                    'コース ID', 'グループ', 'コース名'
                ];
                break;
            default:
                dd('error on Edit', $object);
                break;
        }
        $btnSave = '.btn-save';

        $this->pressEditButton($browser);

        $this->seeList($browser, $listAssertSee);

        $browser->pause(1000);

        $edit = true;

        if ($object == 'course-tab') {
            if ($this->resultToCheck) {
                $edit = true;
            } else {
                $edit = false;
            }
        }

        $obj = $this->fillForm($browser, $object, $edit);

        $browser
            ->press($btnSave)
            ->pause(4000);

        $this->seeList($browser, $obj);

        $browser->pause(2000);

    }

    private function fillForm($browser, $object, $edit = false)
    {
        switch ($object) {
            case 'driver':
                return $this->fillFormDriver($browser, $edit);
            case 'course-tab':
                return $this->fillFormCourseTab($browser, $edit);
            default:
                dd('not found case');
                break;
        }
    }

    private function fillFormCourseTab($browser, $edit = true)
    {
        $rowNumber = 1;
        $rowElement = "tbody tr:nth-child($rowNumber)";
        if ($browser->element($rowElement)) {
            $code = '';
            $name = '';
            if ($browser->element($rowElement . " td:nth-child(1)")) {
                $code = $browser->text($rowElement . " td:nth-child(1)");
            }
            if ($browser->element($rowElement . " td:nth-child(3)")) {
                $name = $browser->text($rowElement . " td:nth-child(3)");
            }
            $daysInMonth = Carbon::parse($this->currentDate)->daysInMonth;
            $colEdited = '';
            $day = '';
            // search empty cell to edit
            for ($col = 4; $col <= $daysInMonth + 3; $col ++) {
                $day = " td:nth-child($col)";
                $column = $rowElement . $day;
                if ($browser->element($column)) {
                    $firstDiv = $column . " .node-course-base";
                    if ($browser->element($firstDiv)) {
                        if (!$edit) {
                            if ($browser->text($firstDiv) == '-') {
                                $colEdited = $column;
                                break;
                            }
                        } else {
                            if ($browser->text($firstDiv) != '-') {
                                $colEdited = $column;
                                break;
                            }
                        }

                    }
                }
            }
            if ($colEdited) {
                $browser->press($colEdited);

                $wrapCheckbox = "#select-day-off";

                $browser->waitFor($wrapCheckbox);

                if ($browser->elements($wrapCheckbox . " .custom-radio")) {
                    $checkboxes = $browser->elements($wrapCheckbox . " .custom-radio");
                    $checkboxSelected = rand(2, count($checkboxes));

                    $checkboxElement = $wrapCheckbox . " .custom-radio:nth-child($checkboxSelected) label";
                    $spanElement = $checkboxElement ." span";
                    if ($browser->element($spanElement)) {
                        $driverName = $browser->text($spanElement);
                    }

                    $browser->pause(3000)->scrollIntoView($checkboxElement)->press($checkboxElement);
                    $browser->pause(3000)->scrollIntoView('.zone-save .btn-save')->press('.zone-save .btn-save');
                    return $this->resultToCheck = [
                        'code' => $code,
                        'name' => $name,
                        'element' => 'table:nth-child(2) ' . $colEdited . " .node-course-base",
                        'textInElement' => $driverName ?? '-',
                        'day' => $day
                    ];
                }
            }
        } else {
          return [];
        }
    }

    private function sortFields($browser, $tblIndex = 1)
    {
        $browser->pause(2000);
        $browser
            ->press("table:nth-child($tblIndex) .th-employee-number")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .th-employee-number")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .th-type-employee")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .th-type-employee")
            ->pause(4000);
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

    private function list($browser, $object = 'shift', $shiftView = 'week')
    {
        // case $object == 'shift':
        $item1 = 1;
        $item2 = 1;
        $listAssertSee = ['シフト表', 'Crew番号', 'Crew区分', 'Crew名'];
        switch ($object) {
            case 'course':
                $item1 = 2;
                $item2 = 2;
                $listAssertSee = [ 'Crew情報', 'Crew番号', 'Crew名', '在籍状況', '詳細', '削除'];
                break;
        }
        $element = "ul.item-modules li:nth-child($item1) ul.item-path li:nth-child($item2)";
        $hover = "ul.item-modules > li:nth-child($item1)";
        $browser
            ->pause(1000)
            ->mouseover($hover)
            ->waitFor($element)
            ->click($element)
            ->pause(3000);

        $this->mouseOut($browser);

        $this->seeList($browser, $listAssertSee);

        if ($object == 'shift') {
            $tab1 = ".list-shift__control .btn-group button:nth-child(1)";
            if (
                $browser->element($tab1)
                && strpos($browser->attribute($tab1, 'class'), 'active') === false
            ) {
                $this->goShiftTab($browser);
            }
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
            if (isset($list['element']) && isset($list['textInElement'])) {
                $this->scrollTo($browser, $list['element']);
                $this->scrollTo($browser, 'table:nth-child(2) tr:nth-child(1)');
                $browser->assertSeeIn($list['element'], $list['textInElement']);
            }
        }
    }

    private function setCurrentDate($browser) {
        if ($browser->element('.picker-month-year__time')) {
            $this->currentDate = str_replace([' ', '年', '月', '|'], ['', '', '', '-'], $browser->text('.picker-month-year__time'));
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
}
