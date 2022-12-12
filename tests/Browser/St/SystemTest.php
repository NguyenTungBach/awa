<?php

namespace Tests\Browser\St;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;

class SystemTest extends DuskTestCase
{
    use withFaker;
    private $dataSee;
    private $currentDate;

    public function testUser()
    {
        $this->browse(function ($browser) {

            $this->loginAdmin($browser);
            $browser->pause(4000);
            $browser->assertSee('シフト表');

                $object = 'user';
            $this->list($browser, $object);
            $this->goToCreate($browser, $object);
            $this->goToEdit($browser, $object);
            $this->goToDelete($browser, $object);
            $browser->pause(5000);

        });
    }

    public function testCourse()
    {
        $this->browse(function ($browser) {

            // $this->loginAdmin($browser);
            // $browser->pause(4000);
            // $browser->assertSee('シフト表');
            $object = 'course';
            $this->list($browser, $object);
            $browser->pause(3000);
            $this->goToCreate($browser, $object);
            $this->goToEdit($browser, $object);
            $this->goToViewDetail($browser, $object);
            $browser->pause(5000);
            $this->pressBack($browser, $object);
            $this->goToDelete($browser, $object);
            $browser->pause(5000);

        });
    }

    public function testCoursePattern()
    {
        $this->browse(function ($browser) {
            // $this->loginAdmin($browser);
            // $browser->pause(4000);
            // $browser->assertSee('シフト表');

            $object = 'coursePattern';
            $this->list($browser, $object);
            $browser->press('.btn-edit');
            $browser->pause(5000);
            $this->pressBack($browser);
            $this->goToEdit($browser, $object);

            $browser->pause(5000);

        });
    }

    public function testDriver()
    {
        $this->browse(function ($browser) {
            // $this->loginAdmin($browser);
            // $browser->pause(4000);
            // $browser->assertSee('シフト表');

            $object = 'driver';
            $this->list($browser, $object);
            $this->goToCreate($browser, $object);

            $this->goToEdit($browser, $object);

            $this->updateCoursesForDricer($browser, $object);

            $this->goToDelete($browser, $object);

            $browser->pause(5000);

        });
    }

    public function testSchedule()
    {
        $this->browse(function ($browser) {

            // $this->loginAdmin($browser);
            // $browser->pause(4000);
            // $browser->assertSee('シフト表');

            $object = 'schedule';
            $this->list($browser, $object);
            $this->scrollTo($browser);
            $this->scrollTo($browser, 'tr:first-child');
            $this->pressExportBtn($browser, $object);

            $this->pressEditButton($browser);

            $this->seeList($browser, ['配車情報', 'コースID', 'グループ', 'コース名']);

            $this->pressImportBtn($browser, $object);

            $this->goToEdit($browser, $object);

            $browser->assertAttribute('tr:nth-child(1) td:nth-child(4)', 'class', 'node-base');

            $this->pressEditButton($browser);

            $this->seeList($browser, ['配車情報', 'コースID', 'グループ', 'コース名']);

            $this->pressBack($browser);

            $browser->pause(5000);

        });
    }

    public function testDayOff()
    {
        $this->browse(function ($browser) {

            // $this->loginAdmin($browser);
            // $browser->pause(4000);
            // $browser->assertSee('シフト表');
            $object = 'dayOff';
            $this->list($browser, $object);

            $this->pressEditButton($browser);
            $browser->pause(4000);
            $this->pressBack($browser);
            $browser->pause(4000);

            $this->goToEdit($browser, $object);

            $dataSeeList = $this->dataSee;

            // $this->list($browser, 'shift', 'month');
            // $this->scrollTo($browser, 'tr:first-child td:nth-child(' . $dataSeeList['day'] . ')');
            // $this->scrollTo($browser, 'tr:first-child');
            // $this->seeList($browser, $dataSeeList);
            // $browser->pause(5000);

            $browser->pause(5000);
        });
    }

    public function testShift()
    {
        $this->browse(function ($browser) {
            // $this->loginAdmin($browser);
            // $browser->pause(4000);
            // $browser->assertSee('シフト表');

            $object = 'shift';
            $this->list($browser, $object, 'month');
            $browser
                ->pause(4000)
                ->press('.zone-select-week-month button:nth-child(1)')
                ->pause(3000)
                ->mouseover('#popover-calendar')
                ->pause(5000)
                ->mouseover('.btn-back')->pause(2000)->press('.btn-back')->pause(3000)
                ->mouseover('.btn-next')->pause(2000)->press('.btn-next')->pause(3000)
                ->pause(2000)
                ->mouseover('.picker-month-year__back')->pause(2000)->press('.picker-month-year__back')->pause(3000)
                ->mouseover('.picker-month-year__next')->pause(2000)->press('.picker-month-year__next')->pause(3000)
                ->pause(4000)
                ;
            // call Ai on times has data
            $this->shiftCallAI($browser);
            // call Ai on time hasn't data
            $this->shiftCallAI($browser, true);

            $this->shiftBackMonths($browser);

            $this->pressEditButton($browser);

            $browser->pause(5000);

            $this->pressBack($browser);

            $browser->pause(5000);

            $this->goToEdit($browser, 'shift');

            $browser->pause(5000);

            $browser->press('.btn-excel');

            $browser->pause(5000);

            $browser->press('.btn-pdf');

            $browser->pause(5000);

            $browser->press('.list-shift__control .btn-group button:nth-child(2)')->pause(4000);

            $this->seeList($browser, [
                '実務実績表',
                '1日〜末日までのデータを表示',
                '社員番号',
                '社員区分',
                '従業員名',
                '総拘束時間',
                '総乗車時間',
                '残業時間',
                '出勤日数',
                '休日数',
                '有給休暇',
                '1日最大拘束時間',
                '1日最大乗車時間',
                '15時間以上勤務日数'
            ]);

            $browser->pause(5000);

            $browser->press('.btn-excel');

            $browser->pause(5000);

            $browser->press('.btn-pdf');

            $browser->pause(5000);

            $browser->press('.list-shift__control .btn-group button:nth-child(3)')->pause(4000);

            $this->seeList($browser, [
                '実務実績表',
                '前月21日〜20日までのデータを表示',
                '社員番号',
                '社員区分',
                '従業員名',
                '総拘束時間',
                '総乗車時間',
                '残業時間',
                '出勤日数',
                '休日数',
                '有給休暇',
                '1日最大拘束時間',
                '1日最大乗車時間',
                '15時間以上勤務日数'
            ]);

            $browser->pause(5000);

            $browser->press('.btn-excel');

            $browser->pause(5000);

            $browser->press('.btn-pdf');

            $browser->pause(5000);

            $this->logOutAdmin($browser);

        });
    }

    public function testShiftUser()
    {
            $this->browse(function ($browser) {
                $this->loginAdmin($browser, 'driver');
                $browser->pause(4000);
                $browser->assertSee('シフト表');
                $object = 'shift';
                $this->list($browser, $object, 'month');
                $browser
                    ->pause(3000)
                    ->press('.zone-select-week-month button:nth-child(1)')
                    ->pause(3000)
                    ->mouseover('#popover-calendar')
                    ->pause(5000)
                    ->mouseover('.btn-back')->pause(2000)->press('.btn-back')->pause(3000)
                    ->mouseover('.btn-next')->pause(2000)->press('.btn-next')->pause(3000)
                    ->pause(2000)
                    ->mouseover('.picker-month-year__back')->pause(2000)->press('.picker-month-year__back')->pause(3000)
                    ->mouseover('.picker-month-year__next')->pause(2000)->press('.picker-month-year__next')->pause(3000)
                    ->pause(4000);

                $browser->pause(5000);

                $browser->press('.btn-excel');

                $browser->pause(5000);

                $browser->press('.btn-pdf');

                $browser->pause(5000);

                $this->logOutAdmin($browser);
            });



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

        (new \GuzzleHttp\Client())->post($url . $uri, ['body' => json_encode($body)]);

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
            case 'user':
                $item1 = 2;
                $item2 = 3;
                $listAssertSee = [ 'ユーザー情報', 'ユーザーID', 'ユーザー名', 'ユーザー権限', '詳細', '削除', '新規登録'];
                break;
            case 'course':
                $item1 = 2;
                $item2 = 2;
                $listAssertSee = [ 'コース情報', 'コースID', 'コース名', '始業時間', '終業時間', '休憩時間', '詳細', '削除'];
                break;
            case 'driver':
                $item1 = 2;
                $item2 = 1;
                $listAssertSee = [ '従業員情報', '社員番号', '氏名', '在籍状況', '詳細', '削除'];
                break;
            case 'coursePattern':
                $item1 = 2;
                $item2 = 2;
                $listAssertSee = [ 'コース組み合わせ表', 'コースID', 'コース名'];
                break;
            case 'schedule':
                $item1 = 1;
                $item2 = 3;
                $listAssertSee = [ '配車情報', 'コースID', 'グループ', 'コース名'];
                break;
            case 'dayOff':
                $item1 = 1;
                $item2 = 2;
                $listAssertSee = ['休日希望情報', '社員番号', '社員区分', '氏名'];
                break;

            case 'shift':
                $item1 = 1;
                $item2 = 1;
                $listAssertSee = ['シフト表', '社員番号', '社員区分', '氏名'];
                break;
            default:
                dd('error');
                break;
        }
        $element = "ul.item-modules li:nth-child($item1) ul.item-path li:nth-child($item2)";
        $hover = "ul.item-modules > li:nth-child($item1)";
        if ($object == 'coursePattern') {
            $element = "ul.item-modules li:nth-child($item1) ul.item-path li:nth-child($item2)" . ' ul.item-child li:nth-child(1)';
        }
        $browser
            ->pause(1000)
            ->mouseover($hover);
        if ($object == 'coursePattern') {
            $browser
                ->pause(1500)
                ->mouseover("ul.item-modules > li:nth-child($item1) ul.item-path li:nth-child($item2)")
            ;
        }
        $browser
            ->waitFor($element)
            ->click($element)
            ->pause(5000);

        $this->seeList($browser, $listAssertSee);

        $browser->pause(1000);
        if ($object == 'shift' && $shiftView != 'week') {
            $browser->mouseover('.show-logo');
            $browser->pause(3000);
            $browser->press('.zone-select-week-month button:nth-child(2)')->pause(4000);
        }
    }

    private function goToCreate($browser, $object)
    {
        switch ($object) {
            case 'user':
                $listAssertSee = ['ユーザー新規登録'];
                break;
            case 'course':
                $listAssertSee = ['コース新規登録'];
                break;
            case 'driver':
                $listAssertSee = ['従業員新規登録'];
                break;
            default:
                dd('error');
                break;
        }
        $btnCreateNew = '.btn-color-sign-up';
        if ($object == 'driver') {
            $btnCreateNew = '.btn-sign-up';
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
            case 'user':
                $td = 4;
                $listAssertSee = ['ユーザー詳細', 'ユーザー情報', 'ユーザーID', 'ユーザー名', 'ユーザー権限', 'パスワード'];
                break;
            case 'course':
                $td = 6;
                $listAssertSee = ['コース詳細', '基本情報', 'コースID', 'グループ', 'コース名', '始業時間', '休憩時間', '開始日', '終業時間', '疲労度', '終了日', 'メモ'];
                break;
            case 'driver':
                $td = 4;
                $listAssertSee = ['基本情報', '社員番号', '氏名', '入社日', '生年月日', '勤務可能日数', '固定休曜日', '労働時間', '退職日', 'メモ'];
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
            case 'user':
                $listAssertSee = [ 'ユーザー情報', 'ユーザーID', 'ユーザー名', 'ユーザー権限', 'パスワード' ];
                break;
            case 'course':
                $listAssertSee = [ 'コース編集', '基本情報', '送迎配送', 'コースID', 'グループ', 'コース名', '始業時間', '終業時間', '休憩時間', '疲労度', '開始日', '終了日', 'メモ' ];
                break;
            case 'driver':
                $listAssertSee = $sees;
                break;
            case 'coursePattern':
                $listAssertSee = ['コース組み合わせ表', 'コースID', 'コース名'];
                break;
            case 'schedule':
                $listAssertSee = ['配車情報', 'コースID', 'グループ', 'コース名'];
                break;
            case 'dayOff':
                $listAssertSee = ['休日希望情報', '社員番号', '社員区分', '氏名'];
                break;
            case 'shift':
                $listAssertSee = ['シフト表', '社員番号', '社員区分', '氏名'];
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

    private function updateCoursesForDricer($browser, $object)
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

            $browser->press('.zone-btn-add button')->pause(3000);

            $cntRows = count($browser->driver->findElements(WebDriverBy::xpath('//tr'))) - 2;

            $lastRow = "tr:nth-child($cntRows)";



            $btnDelete = $lastRow . " td:nth-child(1) button";

            $this->scrollTo($browser, $lastRow);
            // $cnt = count($browser->driver->findElements(WebDriverBy::xpath('//*[@class="fas fa-minus"]')));

            if ($browser->element($btnDelete)) {
                $browser->press($btnDelete)->pause(2000);
            }

            $browser->press('.zone-btn-add button')->pause(1000);
            $selectCourses = $lastRow . ' > td:nth-child(2) select';
            $this->scrollTo($browser);
            $dataSelected = [];

            if ($browser->element($selectCourses)) {
                $optionSelected = 3;
                $dataSelected[] = $browser->text($selectCourses . " > option:nth-child($optionSelected)");
                $browser->select($selectCourses, $browser->attribute($selectCourses . " > option:nth-child($optionSelected)", 'value'))->pause(5000);
            }

            $browser->press($btnSave)->pause(3000);

            $this->seeList($browser, $dataSelected);

            $this->pressBack($browser, 2);

        }

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

    private function scrollTo($browser, $element = 'tr:last-child td:last-child')
    {
        if ($browser->element($element)) {
            $browser
                ->pause(1000)
                ->scrollIntoView($element)
                ->pause(4000);
        }

    }

    private function seeList($browser, $list = [])
    {
        if ($list && is_array($list) && !empty($list)) {
            foreach ($list as $k => $see) {
                if ($k == 'element' || $k == 'textInElement' || 'day') {
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

    private function fillForm($browser, $object, $edit = false)
    {
        switch ($object) {
            case 'user':
                return $this->fillFormUser($browser, $edit);
                break;
            case 'course':
                return $this->fillFormCourse($browser, $edit);
                break;
            case 'driver':
                return $this->fillFormDriver($browser, $edit);
                break;
            case 'coursePattern':
                return $this->fillFormPattern($browser, $edit);
                break;
            case 'schedule':
                return $this->fillFormSchedule($browser, $edit);
                break;
            case 'dayOff':
                $result = $this->fillFormDayOff($browser, $edit);
                if (!$result) {
                    $result = $this->fillFormDayOff($browser, $edit, false);
                    $browser->press('.btn-save')->pause(3000);
                    $this->pressBack($browser);
                    $browser->pause(5000);
                    $this->pressEditButton($browser);
                    $result = $this->fillFormDayOff($browser, $edit);
                }
                return $result;
                break;
            case 'shift':
                $resultCase1 = $this->fillFormShift($browser, $edit, 1);

                $browser->press('.btn-save')->pause(5000);
                $this->seeList($browser, $resultCase1);
                $browser->pause(5000);
                $this->pressEditButton($browser);

                return $this->fillFormShift($browser, $edit, 2);
                break;
            default:
                dd('not found case');
                break;
        }
    }

    private function fillFormShift($browser, $edit, $case = 1)
    {
        if ($case == 1) {
            $column = "";
            $colNumber = 0;
            $checkValid = '';

            for($r = 1; $r <= 5; $r ++) {
                $rowTmp = "tr:nth-child($r)";
                for($col = 4; $col <= 10; $col ++) {
                    $columnTmp = $rowTmp . " td:nth-child($col)";
                    if ($browser->element($columnTmp) && $browser->element($columnTmp . " .show-node div > .show-course:nth-child(2)")) {
                        $column = $columnTmp;
                        $colNumber = $col;
                        $checkValid = $columnTmp . " .show-node div > .show-course:nth-child(2)";
                        break 2;
                    }
                }

            }

            if ($column && $browser->element($column)) {
                if ($browser->element($checkValid)) {
                    $browser
                        ->press($column)
                        ->pause(2000)
                        ->waitFor('#modal-detail')
                        ->pause(1000)
                    ;

                    $browser->press('#modal-detail .btn-change')
                        ->pause(2000)
                        ->waitFor('#modal-edit')
                        ->assertSee('シフトを選択');

                    $editFormBody = ".edit-node-list-shift";

                    $optionUpdated = $editFormBody . ' div:nth-child(1) select option[value="00007"]';

                    if ($browser->element($editFormBody . " div:nth-child(1) select")) {
                        if ($browser->element($optionUpdated)) {
                            $browser->select($editFormBody . " div:nth-child(1) select", $browser->attribute($optionUpdated, 'value'));
                        } else {
                            dd("not found element [$optionUpdated]");
                        }
                    } else {
                        dd("not found element [$editFormBody div:nth-child(1) select]");
                    }

                    if ($browser->element($editFormBody . " div:nth-child(3) .icon-delete")) {
                        $browser->press($editFormBody . " div:nth-child(3) .icon-delete");
                        $browser->press($editFormBody . " div:nth-child(2) .icon-delete");
                    }

                    $browser->pause(4000)->press('#modal-edit .btn-save')->pause(3000);

                    return $this->dataSee = [
                        'element' => $column . " .show-node div > .show-course:nth-child(1)",
                        'textInElement' => $browser->text($column . " .show-node div > .show-course:nth-child(1)"),
                        'day' => $colNumber
                    ];

                } else {
                    dd("not found div tag in column[$column .show-node div > .show-course:nth-child(2)]");
                }
            } else {
                dd("not found element [$column]");
            }
        }

        if ($case == 2) {
            $column = "";
            $colNumber = 0;
            $checkValid = '';

            for($r = 1; $r <= 5; $r ++) {
                $rowTmp = "tr:nth-child($r)";
                for($col = 4; $col <= 10; $col ++) {
                    $columnTmp = $rowTmp . " td:nth-child($col)";
                    if ($browser->element($columnTmp) && $browser->element($columnTmp . " .show-node div > .show-course:nth-child(1)") && !$browser->element($columnTmp . " .show-node div > .show-course:nth-child(2)")) {
                        if ($browser->text($columnTmp . " .show-node div > .show-course:nth-child(1)") == '待機') {
                            $column = $columnTmp;
                            $colNumber = $col;
                            $checkValid = $columnTmp . " .show-node div > .show-course:nth-child(1)";
                            break 2;
                        }
                    }
                }
            }

            if ($column && $browser->element($column)) {
                if ($browser->element($checkValid)) {
                    $browser
                        ->press($column)
                        ->pause(2000)
                        ->waitFor('#modal-detail')
                        ->pause(1000)
                    ;

                    $browser->press('#modal-detail .btn-change')
                        ->pause(2000)
                        ->waitFor('#modal-edit')
                        ->assertSee('シフトを選択');

                    $editFormBody = ".edit-node-list-shift";

                    if ($browser->element($editFormBody . " div:nth-child(1) .icon-delete")) {
                        $browser->press($editFormBody . " div:nth-child(1) .icon-delete");
                    }

                    $browser->press('.zone-add span')->pause(1000);

                    $optionUpdated = $editFormBody . ' div:nth-child(1) select option[value="R"]';

                    if ($browser->element($editFormBody . " div:nth-child(1) select")) {
                        if ($browser->element($optionUpdated)) {

                            $browser->select($editFormBody . " .edit-item:nth-child(1) select", $browser->attribute($optionUpdated, 'value'))->pause(3000);
                            $elm = $editFormBody . " .edit-item:nth-child(1) .select-time .item-time:nth-child(1) .select-multiple input";
                            $this->setSelects($browser, $elm, 10, 1);
                            $browser->press('#modal-edit .edit-node');

                            $elm = $editFormBody . " .edit-item:nth-child(1) .select-time .item-time:nth-child(2) .select-multiple input";
                            $this->setSelects($browser, $elm, 14, 1);
                            $browser->press('#modal-edit .edit-node');

                            $elm = $editFormBody . " .edit-item:nth-child(1) .select-time .item-time:nth-child(3) .select-multiple input";
                            $this->setSelects($browser, $elm, 1, 1);
                            $browser->press('#modal-edit .edit-node');
                        } else {
                            dd("not found element [$optionUpdated]");
                        }
                        $browser->press('.zone-add span')->pause(1000);
                        $optionUpdated = $editFormBody . ' div:nth-child(2) select option[value="S-2"]';
                        if ($browser->element($editFormBody . " div:nth-child(2) select")) {
                            if ($browser->element($optionUpdated)) {
                                $browser->select($editFormBody . " .edit-item:nth-child(2) select", $browser->attribute($optionUpdated, 'value'))->pause(3000);
                                $elm = $editFormBody . " .edit-item:nth-child(2) .select-time .item-time:nth-child(1) .select-multiple input";
                                $this->setSelects($browser, $elm, 14, 1);
                                $browser->press('#modal-edit .edit-node');

                                $elm = $editFormBody . " .edit-item:nth-child(2) .select-time .item-time:nth-child(2) .select-multiple input";
                                $this->setSelects($browser, $elm, 19, 1);
                                $browser->press('#modal-edit .edit-node');

                                $elm = $editFormBody . " .edit-item:nth-child(2) .select-time .item-time:nth-child(3) .select-multiple input";
                                $this->setSelects($browser, $elm, 2, 1);
                                $browser->press('#modal-edit .edit-node');
                            } else {
                                dd("not found element [$optionUpdated]");
                            }
                        } else {
                            dd("not found element [$editFormBody div:nth-child(2) select]");
                        }
                    } else {
                        dd("not found element [$editFormBody div:nth-child(1) select]");
                    }



                    $browser->pause(4000)->press('#modal-edit .btn-save')->pause(2000);

                    return $this->dataSee = [
                        'element' => $column . " .show-node div > .show-course:nth-child(1)",
                        'textInElement' => '社内業務',
                        'day' => $colNumber
                    ];

                }
            }
        }

    }

    private function fillFormDayOff($browser, $edit, $elementFixDayOff = true)
    {
        $browser->pause(2000);
        $row = 'tr:nth-child(1)';
        if (!$browser->element($row)) {
            dd("not found element [$row]");
        }
        $selectedValue = 2;
        $class = ['base-node', 'node-default', 'base-node-hover'];
        $element = '';
        if ($elementFixDayOff) {
            $class = ['base-node', 'node-fixed-day-off', 'base-node-hover'];
            $selectedValue = 1;
        }
        $colFound = 0;
        for ($d = 1; $d <= Carbon::now()->daysInMonth; $d ++) {
            $col = $d + 3;
            if (
                $browser->element($row . " > td:nth-child($col)")
                &&
                $browser->attribute($row . " > td:nth-child($col)", 'class') == implode(' ', $class)
            ) {
                $colFound = $col;
                $element = $row . " > td:nth-child($col)";
                break;
            }
        }


        if ($colFound && $element && $browser->element($element)) {
            $this->scrollTo($browser, $element);
            $this->scrollTo($browser, 'tr:first-child');
            $browser
                ->press($element)
                ->pause(2000)
                ->assertSee('選択変更');
            // ->press('[name="type_list_schedule_no_active"] + label')
            $textLabelSelected = $browser->text(".custom-radio:nth-child($selectedValue) label > span");

            $browser->press(".custom-radio:nth-child($selectedValue) label")
                ->pause(2000)
                ->press('.zone-save > .btn-save')
                ->pause(3000)
                // ->press($element . ' + td')
                ;
            if ($selectedValue === 1) {
                $class = ['base-node', 'node-default', 'base-node-hover'];
            } else {
                $class = ['base-node', 'node-fixed-day-off', 'base-node-hover'];
            }

            $browser->assertAttribute($element, 'class', implode(' ', $class));
            $browser->assertSeeIn($element . ' span', $textLabelSelected);

        } else {
            return $this->dataSee = [];
            // dd('element [' . $element . '] not found in row ' . $row);
        }

        return $this->dataSee = [
            'code' => $browser->text( $row . ' td:nth-child(1)'),
            'name' => $browser->text( $row . ' td:nth-child(3)'),
            'element' => $element . ' span',
            'textInElement' => $textLabelSelected,
            'day' => $colFound
        ];
    }

    private function fillFormSchedule($browser, $edit)
    {
        $firstRow = 'tr:nth-child(1)';
        $firstElement = $firstRow . ' td:nth-child(4)';
        $browser
            ->press($firstElement)
            ->pause(2000)
            ->assertSee('選択変更')
            // ->press('[name="type_list_schedule_no_active"] + label')
            ->press('[name="type_list_schedule_active"] + label')
            ->pause(2000)
            ->press('.zone-save > .btn-save')
            ->pause(3000)
            // ->press($firstElement . ' + td')
            ;

        return $this->dataSee = [
            'code' => $browser->text( $firstRow . ' td:nth-child(1)'),
            'name' => $browser->text( $firstRow . ' td:nth-child(3)')
        ];
    }

    private function pressExportBtn($browser, $object)
    {
        $browser
            ->pause(1000)
            ->assertSee('Excel出力')
            ->press('.zone-item .item-function')
            ->pause(2000)
        ;
    }

    private function pressImportBtn($browser, $object)
    {
        $browser
            ->pause(1000)
            ->assertSee('Excel取り込み')
            ->press('.zone-item .item-function')
            ->waitFor('#modal-import')
            ->pause(2000)
        ;
        $this->seeList($browser, ['配車情報データを取り込む', 'ファイル', 'ファイルを選択']);

        $file = '配車表_{' . date('Y') . '_' . date('m') . '}.xlsx';

        if (Storage::exists($file)) {
            $browser->attach('#import-file', Storage::path($file))
                    ->pause(3000)
                    ->assertSee($file)
                    ->pause(2000);
        } else {
            dd('not found file: ' . $file . ' on import schedule');
        }
        $fileUpload = $browser->value('#import-file');
        if ($fileUpload != '') {
            $browser->press('.btn-color-active-import')
                ->pause(3000);
        }

        $this->pressBack($browser);

    }

    private function fillFormPattern($browser, $edit)
    {
        $browser
            ->pause(1000)
            ->press('tr:first-child td:nth-child(4)')
            ->waitFor('#modal-edit')
            ->assertSee('コース組み合わせ表')
            ->pause(1000)
            ->press('.custom-radio:nth-child(1) label')
            ->pause(1000)
            ->press('#modal-edit .btn-save')
            ->pause(1000)

            ->press('tr:first-child td:nth-child(5)')
            ->waitFor('#modal-edit')
            ->assertSee('コース組み合わせ表')
            ->pause(1000)
            ->press('.custom-radio:nth-child(1) label')
            ->pause(1000)
            ->press('#modal-edit .btn-save')
            ->pause(1500)
        ;

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
            $browser->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('-2 months', '-1 months')->format('Y-m-d'));

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

            $browser->press('.custom-radio:nth-child(1) label');

            // set fullname
            $browser->typeSlowly('#input-fullname', $this->faker->firstName . ' ' . $this->faker->lastName);


            // set start date
            $browser->typeSlowly('#input-date-hire-date', $this->faker->dateTimeBetween('-2 months', '-1 months')->format('Y-m-d'));

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
                ->typeSlowly('#input-retirement-date', $this->faker->dateTimeBetween('+10 days', '+20 days')->format('Y-m-d'));
        }

        $browser
            ->scrollIntoView('.btn-save')
            ->pause(1000);

        return $this->dataSee = [
            'code' => $browser->value('#input-empolyee-number'),
            'name' => $browser->value('#input-fullname')
        ];
    }

    private function fillFormCourse($browser, $edit)
    {
        $browser->press('label[for="input-shuttle-delivery"]');
        if (!$edit) {
            $code = $this->faker->randomNumber(5);
            $browser
                ->typeSlowly('#input-course-id', $code);
        }
        $code = $browser->value('#input-course-id');
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;

        $this->setSelects($browser, 'input-group-code', 1, 1);
        $browser->typeSlowly('#input-course-name', $name)->pause(1000);

        $this->setSelects($browser, 'input-start-time', 9, $edit ? 3: 1);
        $this->setSelects($browser, 'input-end-time', 18, $edit ? 3: 1);
        $labelForBreak = !$edit ? 'input-break-time': 'input-break_time';
        $this->setSelects($browser, $labelForBreak, $edit ? 2: 1, 3);
        $startDate = $this->faker->dateTimeBetween('-15 days', '-1 days')->format('Y-m-d');
        $endDate = $this->faker->dateTimeBetween('+ 1 years', '+ 2 years')->format('Y-m-d');
        $browser
            ->select('label[for="input-fatigue"] + select', $edit ? 2: 1)
            ->typeSlowly('#input-start-date', $startDate)
            ->typeSlowly('#input-end-date', $endDate)
            ->typeSlowly('#input-notes', $this->faker->text(50))
        ;

        return $this->dataSee = [
            'code' => $code,
            'name' => $name
            // 'group' => 'AA',
            // 'input-start-time' => $edit ? '08:30': '08:00',
            // 'input-end-time' => $edit ? '17:30': '17:00',
            // 'input-start-time' => $edit ? '01:30' : '00:30',
            // 'start-date',
            // 'end-date'
        ];
    }

    private function fillFormUser($browser, $edit = false)
    {

        if (!$edit) {
            $code = $this->faker->randomNumber(4);
            $browser
                ->typeSlowly('#input-user-id', $code);
        }
        $code = $browser->value('#input-user-id');
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $role = 'admin';
        $passWord = $this->faker->passthrough('abb123456');
        $browser
            ->typeSlowly('#input-user-name', $name)
            ->select('#input-user-authority', $role)
            ->typeSlowly('#input-user-password', $passWord)
            ->pause(1000)
        ;
        return $this->dataSee = [
            'code' => $code,
            'name' => $name
            // 'role' => $role
        ];
    }

    private function setSelects($browser, $labelFor, $firstIndex, $secondIndex)
    {
        $element = "label[for='$labelFor'] + div";
        if (!$browser->element($element)) {
            $element = $labelFor;
        }
        if ($browser->element($element)) {
            $browser
                ->press($element)
                ->waitFor('.list-select.show');
        } else {
            dd("not found element [$element] on function setSelects");
        }


        $item1 = '.list-select.show .select-option:nth-child(1) .list-option li:nth-child(' . $firstIndex . ')';
        $class1 = $browser->attribute($item1 . ' > .item-option', 'class');
        $browser
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView($item1);

        if ($class1 == 'item-option'){
            $browser->press($item1);
        } else {
            $browser->press($item1);
            $browser->press($item1);
        }

        $item2 = '.list-select.show .select-option:nth-child(2) .list-option li:nth-child(' . $secondIndex . ')';
        $class2 = $browser->attribute($item2 . ' > .item-option', 'class');
        $browser
            ->mouseover('.list-select.show .select-option:nth-child(2)')
            ->scrollIntoView($item2);

        if ($class2 == 'item-option'){
            $browser->press($item2);
        } else {
            $browser->press($item2);
            $browser->press($item2);
        }
    }

    private function shiftBackMonths($browser)
    {
        $row = 'tr:nth-child(1)';
        $column = $row . ' td:nth-child(4)';
        if ($browser->element($column . ' .show-node div:nth-child(1)')) {
            if (!$browser->text($column . ' .show-node div:nth-child(1)') && $this->currentDate) {
                if (strtotime(date('Y-m' , strtotime($this->currentDate)))) {
                    $diffMonths = Carbon::parse(date('Y-m-d' , strtotime(date('Y-m' , strtotime($this->currentDate)))))->diffInMonths(date('Y-m-d', strtotime(date('Y-m'))));
                    if ($diffMonths > 0) {
                        for ($i = 1; $i <= $diffMonths; $i++) {
                            $browser
                                ->press('.picker-month-year__back')->pause(3000);
                        }
                    }
                }
                // $this->shiftBackMonths($browser);
            } else {
                return true;
            }
        } else {
            dd("not found div tag in col [$column]");
        }
    }

    private function shiftCallAI($browser, $newMonth = false)
    {
        if (!$newMonth) {
            $row = 'tr:nth-child(1)';
            $column = $row . ' td:nth-child(4)';
            if ($browser->element($column . ' .show-node div:nth-child(1)')) {
                if (!$browser->text($column . ' .show-node div:nth-child(1)')) {
                    $empty = 1;
                }
            }
            $this->checkCurrentDate($browser);
            $browser
                ->press('.zone-right .item-function:nth-child(1)')->pause(2000)
                ->waitFor('#modal-ai')->pause(2000)->assertSee('期間を選択してください')
                ->press('#modal-ai .icon-delete')
                ->mouseover('#modal-ai #calendar-multiple-week')
                ->waitFor('.popover-body');



            $firstCalendar = '.popover-body .col-6:nth-child(1)';
            $lastCalendar = '.popover-body .col-6:nth-child(2)';
            if (!$browser->element($firstCalendar) || !$browser->element($lastCalendar)) {
                dd("not found element: [$firstCalendar] or [$lastCalendar]", );
            }

            $fromElement = $firstCalendar . ' .no-gutters:nth-child(2) div:first-child';
            $toElement = $lastCalendar . ' .no-gutters:nth-child(2) div:last-child';
            $startDate = '';
            $endDate = '';
            if ($browser->element($fromElement) && $browser->attribute($fromElement, 'class') == 'col p-0') {
                // $elementClass = $browser->attribute($fromElement, 'class');
                $startDate = $browser->attribute($fromElement, 'data-date');
                if (strtotime(date('Y-m' , strtotime($this->currentDate)))) {
                    $diffMonths = Carbon::parse(date('Y-m' , strtotime($this->currentDate)))->diffInMonths(date('Y-m' , strtotime($startDate)));
                    if ($diffMonths > 0) {
                        for ($i = 1; $i <= $diffMonths; $i++) {
                            $browser->press($firstCalendar . ' .b-calendar-nav button:nth-child(4)');
                            $browser->press($lastCalendar . ' .b-calendar-nav button:nth-child(4)');
                        }
                    }
                } else {
                    dd("current date is : " . $this->currentDate);
                }
                $browser->press($fromElement)->pause(3000);
                if ($browser->element($toElement) && $browser->attribute($toElement, 'class') == 'col p-0') {
                    $endDate = $browser->attribute($toElement, 'data-date');
                    $browser->press($toElement)->pause(4000)->mouseover('#modal-ai .font-weight-bold')->pause(3000)->press('#modal-ai .btn-color-active')->pause(4000);
                    if ($browser->attribute('#modal-confirm-ai', 'class') == 'modal fade show') {
                        if (isset($empty)) {
                            $browser->press('#modal-confirm-ai button:nth-child(2)');
                        } else {
                            $browser->press('#modal-confirm-ai button:nth-child(1)');
                        }

                        $startDate = $endDate = '';
                        $browser->pause(4000);
                    } else {
                        $browser->waitFor('.position-absolute img[alt="Loading"]', 15);

                        $browser->pause(15000);
                        if ($browser->element('#modal-message-response-ai') && $browser->attribute('#modal-message-response-ai', 'class') == 'modal fade show') {
                            $browser->press('.btn-close-message-response-ai');
                        }


                    }
                }
            } else {
                dd('not ok');
            }
        } else {
            $this->nextMonthToCallAI($browser);
            $this->shiftCallAI($browser);
        }
    }

    private function checkCurrentDate($browser)
    {
        if ($browser->element('#popover-calendar')) {
            $browser
                ->mouseover('#popover-calendar')->pause(5000)->waitFor('.popover-body');
            $elementDateFrom = ".popover-body div[aria-selected='true']:nth-child(1)";
            if($browser->element($elementDateFrom)) {
                return $this->currentDate = $browser->attribute($elementDateFrom, 'data-date');

            }
        }
        return false;

    }

    private function nextMonthToCallAI($browser)
    {
        $row = 'tr:nth-child(1)';
        $column = $row . ' td:nth-child(4)';
        if ($browser->element($column . ' .show-node div:nth-child(1)')) {
            if (strlen($browser->text($column . ' .show-node div:nth-child(1)')) > 1) {
                $browser
                ->mouseover('.picker-month-year__next')->pause(2000)->press('.picker-month-year__next')->pause(3000);
                $this->nextMonthToCallAI($browser);
            } else {
                return true;
            }

        } else {
            dd("not found div tag in Column [$column]");
        }
    }

    private function logOutAdmin($browser) {
        Storage::deleteDirectory('downloads');
        $browser->mouseover(".show-profile")->pause(2000)->press('.show-profile li:nth-child(1)')->pause(5000)->assertSee('LOGIN');
    }
}
