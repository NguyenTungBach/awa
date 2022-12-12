<?php

namespace Tests\Browser\Admin;

use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DayoffTest extends DuskTestCase
{
    use withFaker;
    private $currentDate;
    private $lastItemID;
    private $lastItemName;

    public function testDayOff()
    {
        $this->browse(function ($browser) {
            $this->loginAdmin($browser);
            $browser->pause(4000);
            $browser->assertSee('シフト表');

            //view list days off

            $object = 'dayOff';

            $this->list($browser, $object);

            $browser->pause(3000);

            // go to view crew to create new driver

            $object = 'driver';

            $this->list($browser, $object);

            $this->goToCreate($browser, $object);

            // back to list days off to check which item just added

            $object = 'dayOff';

            $this->list($browser, $object);

            $this->scrollTo($browser, 'tbody tr:nth-last-child(1)');

            $this->seeList($browser, [$this->lastItemID, $this->lastItemName]);

            $this->scrollTo($browser);

            //go to update end date, edit days off

            $object = 'driver';

            $this->list($browser, $object);

            $this->goToEdit($browser, $object);

            $this->updateCoursesForDriver($browser, $object);

            // back to list days off to check which item just edited

            $object = 'dayOff';

            $this->list($browser, $object);

            $this->scrollTo($browser, 'tbody tr:nth-last-child(1)');

            $this->seeList($browser, [$this->lastItemID, $this->lastItemName]);

            $this->scrollTo($browser);

            $this->goToEdit($browser, $object);

            // go to list Crew to delete

            $object = 'driver';

            $this->list($browser, $object);

            $this->goToDelete($browser, $object);

            // back to list day off and dont see item just deleted

            $object = 'dayOff';

            $this->list($browser, $object);

            $this->scrollTo($browser, 'tbody tr:nth-last-child(1)');

            $browser->assertDontSee($this->lastItemID);

            $browser->assertDontSee($this->lastItemName);

            $this->scrollTo($browser);

            $browser->pause(3000);

            $this->scrollTo($browser, 'tbody tr:nth-child(1)');

            $this->sortFields($browser);

            $browser->pause(3000);

            $this->nextMonth($browser);

            $browser->pause(3000);

            $this->previousMonth($browser);

            $browser->pause(3000);

            // $this->pressEditButton($browser);

        });
    }

    public function getListDataFromTable($browser, $tblNo = 1)
    {
        $attributes = $browser->driver->findElements(WebDriverBy::cssSelector("table:nth-child($tblNo) tbody tr"));
        $dataTable = [];
        foreach ($attributes as $attribute) {
            $dataTable[] = explode("\n", $attribute->getText());
        }
        unset($dataTable[count($dataTable) - 1]);
        return $dataTable;
    }

    private function createAndValidate($browser)
    {
        $this->scrollTo($browser);

        $tr = 'tbody tr:last-child';
        $lastItemID = $tr . ' td:nth-child(1)';

        if ($browser->element($lastItemID) && $browser->text($lastItemID)) {
            $this->lastItemID = $browser->text($lastItemID);
        }
        $btnCreateNew = '.zone-btn-sign-up button';
        $btnSave = '.btn-save';
        $displayRequiredField  = '入力されていない項目があります。';

        $displayOnCourseCodeDuplicate = 'このCrew番号は既に登録されています。';
        $displayOnCourseCodeMaxlen = 'Crew番号は半角数字4桁で入力してください。';

        $displayOnNameMaxlength = 'Crew名は20文字以下で入力してください。';
        $displayOnStartDateFormatFalse = '入社日の形式が正しくありません。';
        $displayOnEndDateFormatFalse = '生年月日の形式が正しくありません。';
        $itemId = $this->faker->randomNumber(4);
        $itemName = $this->faker->firstName('male') . ' ' . $this->faker->lastName;
        $_1k = 1000;

        $browser
            ->mouseover($btnCreateNew)
            ->press($btnCreateNew)
            ->pause($_1k)
            ->assertSee('Crew新規登録')
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayRequiredField)

            ->press('.custom-radio:nth-child(2) label')
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayRequiredField)

            ->typeSlowly('#input-empolyee-number', $this->lastItemID)
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayRequiredField)

            ->typeSlowly('#input-fullname', $this->faker->text(30))
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayRequiredField);

        // set start date
        $browser->typeSlowly('#input-date-hire-date', 'false date')
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayRequiredField);

        // set date of birth
        $browser->typeSlowly('#input-date-date-of-birth', 'false date')
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayRequiredField);

        // set available day
        $browser->select('#input-available-days', 5)->pause($_1k);
        // set holiday
        $browser
            ->check('.custom-checkbox:nth-child(1) label')
            ->pause($_1k)
            ->check('.custom-checkbox:nth-child(2) label');

        // set note
        $browser
            ->pause($_1k)
            ->scrollIntoView('#input-notes')
            ->pause(2000)
            ->typeSlowly('#input-notes', $this->faker->text(35))
            ->pause($_1k);
        $browser
            ->press($btnSave)
            ->pause(1200)
            ->assertSee($displayOnCourseCodeDuplicate)
            ->typeSlowly('#input-empolyee-number', '0000001')
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayOnCourseCodeMaxlen)
            ->typeSlowly('#input-empolyee-number', $itemId);

        $browser
            ->press($btnSave)
            ->pause(1500)
            ->assertSee($displayOnNameMaxlength)
            ->typeSlowly('#input-fullname', $itemName);

        $browser
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayOnStartDateFormatFalse)
            ->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('+7 days', '+10 days')->format('Y-m-d'));

        $browser
            ->press($btnSave)
            ->pause($_1k)
            ->assertSee($displayOnEndDateFormatFalse)
            ->typeSlowly('#input-date-date-of-birth', $this->faker->dateTimeBetween('-30 years', '-23 years')->format('Y-m-d'));

        $browser
            ->press($btnSave)
            ->pause($_1k + 2000);

        $this->scrollTo($browser);

        $this->seeList($browser, [$itemId, $itemName]);
    }

    private function goToCreate($browser, $object)
    {
        switch ($object) {
            case 'driver':
                $listAssertSee = ['Crew新規登録', '基本情報', 'コース情報'];
                break;
            default:
                dd('error');
                break;
        }
        $btnCreateNew = '.btn-color-sign-up';
        if ($object == 'driver') {
            $btnCreateNew = '.zone-btn-sign-up button';
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
                $listAssertSee = ['Crew詳細', '基本情報', 'コース情報'];
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
        $browser->pause(1000);
        $sees = [];
        if (in_array($object, ['user', 'course', 'driver'])) {
            $sees = $this->goToViewDetail($browser, $object);
        }

        switch ($object) {
            case 'driver':
                $listAssertSee = ['Crew編集', '基本情報', 'コース情報'];
                break;
            case 'dayOff':
                $listAssertSee = ['シフト生成情報表', 'Crew番号', 'Crew区分', 'Crew名'];
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
        if ($object == 'dayOff') {
            $browser->pause(2000);
            foreach ($obj as $item) {
                $this->seeList($browser, $item);
            }
        } else {
            $this->seeList($browser, $obj);
        }



        $browser->pause(2000);

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
            case 'dayOff':
                $results = [];
                $result = $this->fillFormDayOff($browser, $edit);
                $results[$result['day']] = $result;
                $result = $this->fillFormDayOff($browser, $edit, false, false);
                $results[$result['day']] = $result;
                $result = $this->fillFormDayOff($browser, $edit, false, 1);
                $results[$result['day']] = $result;
                return $results;
            default:
                dd('not found case');
        }
    }

    private function fillFormDayOff($browser, $edit, $elementToFixDayOff = true, $nb = 1)
    {
        $row = 'tr:nth-child(2)';
        $optionOff = 'dayoff';
        $optionWorkDefault = 'default';
        $optionWorkCustom = 'work';
        $textOnWorkDefault = '-';
        if ($elementToFixDayOff) {
            $resultFound = $this->chooseCellToEdit($browser, $optionWorkCustom);
            $defaultOption = $optionOff;
        } else {
            $resultFound = $this->chooseCellToEdit($browser);
            $defaultOption = $optionWorkCustom;
            if (!$nb) {
                $defaultOption = $optionWorkDefault;
            }
        }
        $colFound = $resultFound['col'];
        $element = $resultFound['element'];
        if ($colFound && $element && $browser->element($element)) {
            $this->scrollTo($browser, $element);
            $this->scrollTo($browser, 'tr:first-child');
            $selectElement = '.zone-select-day-off select';

            $browser
                ->press($element)
                ->pause(2000)
                ->assertSee('選択変更');

            $browser
                ->select($selectElement, $defaultOption);

            $browser->pause(2000);

            $option = ".custom-radio:nth-child($nb) label";

            if ($browser->element($option)) {
                $browser->press($option);
                $span = $option . ' span';
                if (!$browser->element($span)) {
                    dd("not found span [$span]");
                }
                $textLabelSelected = $browser->text($span);
            } elseif ($defaultOption == 'work' && !$browser->text('#select-course')) {
                $browser
                ->select($selectElement, $optionWorkDefault);
                $textLabelSelected = $textOnWorkDefault;
            }
            $browser
                ->pause(2000)
                ->press('.zone-save > .btn-save')
                ->pause(3000)
                ;
        } else {
            return [];
        }

        return [
            'code' => $browser->text( $row . ' td:nth-child(1)'),
            'name' => $browser->text( $row . ' td:nth-child(3)'),
            'element' => $element . ' span',
            'textInElement' => $textLabelSelected ?? $textOnWorkDefault,
            'day' => $colFound
        ];
    }

    private function chooseCellToEdit($browser, $type = 'off')
    {
        $browser->pause(2000);
        $row = 'tr:nth-child(1)';
        if (!$browser->element($row)) {
            dd("not found element [$row]");
        }
        $class = ['base-node', 'node-fixed-day-off', 'base-node-hover'];
        if ($type !== 'off') {
            $class = ['base-node', 'node-default', 'base-node-hover'];
        }
        for ($d = 1; $d <= Carbon::now()->daysInMonth; $d ++) {
            $col = $d + 3;
            if (
                $browser->element($row . " > td:nth-child($col)")
                &&
                $browser->attribute($row . " > td:nth-child($col)", 'class') == implode(' ', $class)
            ) {
                return [
                    'col' => $col,
                    'element' => $row . " > td:nth-child($col)"
                ];
            }
        }
        return [
            'col' => '',
            'element' => ''
        ];
    }

    private function fillFormDriver($browser, $edit)
    {
        if (!$edit) {
            // $this->assertNotNull($browser->element('input[name="select-type-driver"]'));
            $browser->press('.custom-radio:nth-child(3) label');

            $this->lastItemID = $this->faker->randomNumber(4);

            // set driver code
            $browser->typeSlowly('#input-empolyee-number', $this->lastItemID);

            // set fullname
            $this->lastItemName = $this->faker->firstName . ' ' . $this->faker->lastName;
            $browser->typeSlowly('#input-fullname', $this->lastItemName);

            // set start date
            $browser->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('+1 days', '+3 days')->format('Y-m-d'));

            // set date of birth
            $browser->typeSlowly('#input-date-date-of-birth', $this->faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'));

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

            $browser->press('.custom-radio:nth-child(2) label');

            // set fullname
            $this->lastItemName = $this->faker->firstName . ' ' . $this->faker->lastName;
            $browser->typeSlowly('#input-fullname', $this->lastItemName);


            // set start date
            $browser->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('+ 1 days', '+2 days')->format('Y-m-d'));

            // set date of birth
            $browser->typeSlowly('#input-date-date-of-birth', $this->faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'));

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
                ->typeSlowly('#input-retirement-date', $this->faker->dateTimeBetween('+10 days', '+11 days')->format('Y-m-d'));
        }

        $browser
            ->scrollIntoView('.btn-save')
            ->pause(1000);

        return $this->dataSee = [
            'code' => $browser->value('#input-empolyee-number'),
            'name' => $browser->value('#input-fullname')
        ];
    }

    private function nextMonth($browser) {
        $browser->press('.picker-month-year__next');
    }

    private function previousMonth($browser) {
        $browser->press('.picker-month-year__back');
    }

    private function updateCoursesForDriver($browser, $object)
    {
        $this->goToViewDetail($browser, $object);

        $tabCourses = '.nav-tabs li:nth-child(2) a';

        if ($browser->element($tabCourses)) {
            $browser->press($tabCourses)->pause(1000);


            $assertSeeList = ['走行可能コースID', '走行可能コース名'];
            $this->seeList($browser, $assertSeeList);
            $btnSave = '.zone-control:nth-child(2) .btn-save';
            $this->pressEditButton($browser, 2);

            $browser->pause(2000);

            $this->seeList($browser, ['走行可能コース名']);

            $browser->press('.zone-btn-add button')->pause(1000);

            $lastRow = ".zone-table-driver-course tbody tr:nth-last-child(2)";

            $btnDelete = $lastRow . " td:nth-child(1) button";

            $this->scrollTo($browser, $lastRow);

            if ($browser->element($btnDelete)) {
                $browser->press($btnDelete)->pause(1000);
            }

            $browser->press('.zone-btn-add button')->pause(1000);

            $selectCourses = $lastRow . ' > td:nth-child(2) select';
            $addIsCheck = $lastRow . ' > td:nth-child(3) > .custom-checkbox';
            $this->scrollTo($browser, $lastRow);
            $dataSelected = [];

            if ($browser->element($selectCourses)) {
                $optionSelected = 3;
                $dataSelected[] = $browser->text($selectCourses . " > option:nth-child($optionSelected)");
                $browser->select($selectCourses, $browser->attribute($selectCourses . " > option:nth-child($optionSelected)", 'value'))->pause(1000);
                $browser->press($addIsCheck)->pause(1000);
            }

            $browser->press('.zone-btn-add button')->pause(1000);

            $selectCourses = $lastRow . ' > td:nth-child(2) select';

            $this->scrollTo($browser, $lastRow);

            if ($browser->element($selectCourses)) {
                $optionSelected = 2;
                $dataSelected[] = $browser->text($selectCourses . " > option:nth-child($optionSelected)");
                $browser->select($selectCourses, $browser->attribute($selectCourses . " > option:nth-child($optionSelected)", 'value'))->pause(1000);
            }

            $browser->press($btnSave)->pause(5000);

            $this->seeList($browser, $dataSelected);

            $browser->pause(2000);

            $this->pressBack($browser, 2);

        }

    }

    private function sortFields($browser, $tblIndex = 1)
    {
        $browser->pause(2000);
        $browser
            ->press("table:nth-child($tblIndex) .th-number-employee")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .th-number-employee")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .th-type-employee")
            ->pause(4000)
            ->press("table:nth-child($tblIndex) .th-type-employee")
            ->pause(4000);
    }

    protected function loginAdmin($browser, $permission = 'admin')
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
            ->pause(500)
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
                $listAssertSee = ['シフト表', '社員番号', '社員区分', '氏名'];
                break;
            case 'dayOff':
                $item1 = 1;
                $item2 = 2;
                $listAssertSee = ['シフト希望表', 'Crew番号', 'Crew区分', 'Crew名'];
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
            if (!$this->currentDate) {
                $browser
                ->mouseover('.picker-month-year__next')->pause(500)->press('.picker-month-year__next')->pause(3000)
                ->mouseover('.btn-next')->pause(500)->press('.btn-next')->pause(3000);
            }

            $browser->pause(2000);

            $browser->press('.list-shift__control .btn-group button:nth-child(1)')->pause(4000);

            if (!$this->currentDate) {
                $this->checkCurrentDate($browser);
            }

            $this->mouseOut($browser);

            if ($shiftView != 'week') {
                $browser->press('.zone-select-week-month button:nth-child(2)')->pause(4000);
            }

        }
    }

    private function mouseOut($browser)
    {
        $browser->waitFor('.app-layout')->mouseover('.app-layout');
    }

     private function checkCurrentDate($browser)
    {
        if ($browser->element('#popover-calendar')) {
            $browser
                ->mouseover('#popover-calendar')->pause(2000)->waitFor('.popover-body');
            $elementDateFrom = ".popover-body div[aria-selected='true']:nth-child(1)";
            if($browser->element($elementDateFrom)) {
                return $this->currentDate = $browser->attribute($elementDateFrom, 'data-date');

            }
        }
        return false;

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

    private function scrollTo($browser, $element = 'tbody tr:nth-last-child(1) td:nth-last-child(1)')
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
            $element = ".zone-control:nth-child($nb) .btn-edit";
            $btnEdit = $browser->element($element) ? $element: '';
        } else {
            $element = ".btn-edit";
            $btnEdit = $browser->element() ? ".btn-edit": '';
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
