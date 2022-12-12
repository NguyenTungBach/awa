<?php

namespace Tests\Browser\Admin;

use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;



class ScheduleTest extends DuskTestCase
{
    // use DatabaseMigrations;
    public function testScheduleList()
    {
        // self::makeFakeData();
        $this->browse(function ($browser) {
            $this->loginAdmin($browser);
            $browser->pause(4000);
            $this->listSchedule($browser);
            $this->sortByCourseId($browser);
            $browser->pause(1000);
            $this->sortByCourseGroup($browser);
            $browser->pause(1000);
            $this->showPreviousMonth($browser);
            $browser->pause(1000);
            $this->showNextMonth($browser);
            $browser->pause(1000);
            $this->exportSchedule($browser);

            $browser->pause(1000);
            $this->goToUpdateSchedule($browser);
            $browser->pause(2000);
        });
    }
    public function testScheduleEdit()
    {
        $this->browse(function ($browser) {
            $browser->pause(2000);
            $this->submitEmptyWhenImportFile($browser);
            $this->validateImportIsWrongFormatFile($browser);
            $this->importScheduleFromCsvSuccessWithInvalidCourse($browser);
            $this->importScheduleFromCsvSuccess($browser);
            $this->updateSchedule($browser);

            $this->checkEditCourse($browser);
            $this->checkEditCourse($browser, true);
            $this->checkEditCourse($browser);
            $this->checkEditCourse($browser, false, true);
            $browser->pause(5000);
        });
    }
    private function loginAdmin($browser)
    {
        $browser->visit('/login')
            ->pause(10000)
            ->typeSlowly("#user_id",'1122')
            ->typeSlowly('#password', 'abc12345678')
            ->press(".login-btn")->pause(1000);
    }

    private function listSchedule($browser)
    {
        $element = 'ul.item-modules li:nth-child(1) ul.item-path li:nth-child(3)';
            $browser
                ->mouseover('ul.item-modules > li:first-child')
                ->waitFor($element)
                ->click($element)
                ->pause(3000)
                ->assertSee('配車情報')->press('.show-logo');
    }

    private function sortByCourseId($browser)
    {
        $element = 'th.th-course-id:first-child';
        $browser
            ->mouseover($element)
            ->pause(2000)
            ->press($element)
            ->pause(2000)
            ->press($element)
            ->pause(2000);

    }

    private function sortByCourseGroup($browser)
    {
        $element = 'th.th-course-group-code:nth-child(2)';
        $browser
            ->mouseover($element)
            ->pause(4000)
            ->press($element)
            ->pause(3000)
            ->press($element)
            ->pause(2000);
    }
    private function showPreviousMonth($browser)
    {
        $element = '.picker-month-year__back';
        $browser
            ->mouseover($element)
            ->pause(3000)
            ->press($element)
            ->pause(4000)
            ->assertSee('配車情報');
    }
    private function showNextMonth($browser)
    {
        $element = '.picker-month-year__next';
        $browser
            ->mouseover($element)
            ->pause(3000)
            ->press($element)
            ->pause(4000)
            ->assertSee('配車情報');
    }
    private function exportSchedule($browser)
    {
        $element = '.item-function';
        $browser
            ->mouseover($element)
            ->press($element)
            ->pause(4000);
            // ->assertSee('配車情報') //配車情報
            // ->assertPathIs('/shift-management/list-schedule-edit');
    }
    private function goToUpdateSchedule($browser)
    {
        $element = '.btn-edit';
        $browser
            ->mouseover($element)
            ->press($element)
            ->pause(2000)
            // ->assertSee('配車情報') //配車情報
            ->assertPathIs('/shift-management/list-schedule-edit');
    }
    private function openImportModal($browser)
    {
        $element = '.item-function';
        $browser
            ->press($element)
            ->pause(1000)
            ->assertSee('配車情報データを取り込む')
            ->pause(1000);
    }
    private function validateImportIsWrongFormatFile($browser)
    {
        $this->openImportModal($browser);
        $file = 'fun.pdf';
        if (Storage::exists($file)) {
            $browser->attach('#import-file', Storage::path($file))
                    ->pause(2000)
                    ->assertSee('ファイルの形式が正しくありません')
                    ->pause(2000);
        }
        $file = 'japan_fonts.xlsx';
        if (Storage::exists($file)) {
            $browser->attach('#import-file', Storage::path($file))
                    ->pause(2000)
                    ->assertSee('ファイルの容量が大きすぎます')
                    ->pause(3000)
                    ->press('#modal-import___BV_modal_body_ .btn-secondary');
        }
    }
    private function submitEmptyWhenImportFile($browser)
    {
        $this->openImportModal($browser);
        $browser
            ->pause(1000)
            ->press('.btn-color-active-import')
            ->pause(2000)
            ->assertSee('エラー')
            ->pause(2000);
    }
    private function importScheduleFromCsvSuccess($browser)
    {
        $this->openImportModal($browser);
        $file = '配車表_{2022_10}.xlsx';
        if (Storage::exists($file)) {
            $browser->attach('#import-file', Storage::path($file))
                    ->pause(3000)
                    ->assertSee($file)
                    ->pause(2000);
        }
        $fileUpload = $browser->value('#import-file');
        if ($fileUpload != '') {
            $browser->press('.btn-color-active-import')
                ->pause(3000);
        }
    }
    private function importScheduleFromCsvSuccessWithInvalidCourse($browser)
    {
        $this->openImportModal($browser);
        $file = '配車表_{2022_10}_invalid_course.xlsx';
        if (Storage::exists($file)) {
            $browser->attach('#import-file', Storage::path($file))
                    ->pause(3000)
                    ->assertSee($file)
                    ->pause(2000);
        }
        $fileUpload = $browser->value('#import-file');
        if ($fileUpload != '') {
            $browser->press('.btn-color-active-import')
                ->pause(10000)
                ->assertSee('はインポートできませんでした。')
                ->press('#modal-import-faild___BV_modal_body_ .btn-secondary')
            ;
        }
    }
    private function updateSchedule($browser)
    {
        $element = 'td.node-base.node-base-hover';
        $browser
            ->press($element)
            ->pause(2000)
            ->assertSee('選択変更')
            ->press('[name="type_list_schedule_no_active"] + label')
            ->press('[name="type_list_schedule_active"] + label')
            ->pause(2000)
            ->press('.zone-save > .btn-save')
            ->pause(2000)


            ->press($element . ' + td')
            ->pause(3000)
            ->assertSee('選択変更')
            ->pause(1000)
            ->press('[name="type_list_schedule_active"] + label')
            ->press('[name="type_list_schedule_no_active"] + label')
            ->pause(2000)
            ->press('.zone-save > .btn-save')
            ->pause(2000)

            ->press('.page-schedule__body .zone-control .btn-save')
            ->pause(2000)
            ->assertSee('成功')
            ->pause(10000);
    }

    private function checkEditCourse($browser, $checkFlag = false, $deleteCourse = false)
    {
        $this->listCourse($browser);

        if (!$deleteCourse) {
            $this->viewCourseDetail($browser);

            $this->updateCourseFields($browser, $checkFlag);
        } else {
            $this->deleteCourse($browser);
        }

        $this->listSchedule($browser);

        $browser->pause(2000);

    }
    private function deleteCourse($browser)
    {
        $element = 'tr td:nth-child(7)';

        $browser
            ->pause(2000)
            ->press($element)
            ->pause(2000)
            ->waitFor('#modal-delete')
            ->assertSee('確認')
            ->assertSee('本当に削除しますか？')
            ->pause(1000)
            ->press('#modal-delete .btn-primary')
            ->pause(2000)
            ->assertSee('成功')
            ->pause(1000)
            ->assertSee('コース情報');

    }

    private function listCourse($browser)
    {
        $element = 'ul.item-modules li:nth-child(2) ul.item-path li:nth-child(2)';
            $browser
                ->mouseover('ul.item-modules > li:nth-child(2)')
                ->waitFor($element)
                ->click($element)
                ->pause(1000)
                ->assertSee('コース情報')->press('.show-logo');
    }

    private function viewCourseDetail($browser)
    {
        $element = 'tr td:nth-child(6)';

        $browser
            ->pause(3000)
            ->waitFor($element)
            ->press($element)
            ->pause(2000)
            ->assertSee('コース詳細')
            ->pause(1000);

    }

    private function updateCourseFields($browser, $checkFlag = false)
    {
        $element = '.btn-edit';
        $browser
            ->pause(2000)
            ->press($element)
            ->pause(1000)
            ->assertSee('コース編集')
            ->pause(1000);

        $theCourse = Course::where('course_code', $browser->value('#input-course-id'))->first();

        if (!$checkFlag) {

            if ($theCourse->flag) {
                $browser->press('label[for="input-shuttle-delivery"]');
            }
            $browser
                ->typeSlowly('#input-start-date', '2022-10-05')
                ->typeSlowly('#input-end-date', '2022-10-25');
        } else {
            if (!$theCourse->flag) {
                $browser->press('label[for="input-shuttle-delivery"]');
            }
        }
        $browser
            ->press('.btn-save')
            ->pause(2000)
            ->assertSee('成功')
            ->pause(2000);

    }



}
