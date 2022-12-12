<?php

namespace Tests\Browser\Admin;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CourseTest extends DuskTestCase
{
    public function testCourse()
    {
        $this->browse(function ($browser) {

            $this->loginAdmin($browser);
            $browser->pause(4000);
            $this->list($browser);
            $this->actionFromCourse($browser);
            $browser->pause(3000);
        });
    }
    private function list($browser)
    {
        $element = '.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(2) > a';
        $browser->pause(5000)
            ->mouseover('.app-layout > div.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2)')
            ->waitFor($element)
            ->click($element)
            ->pause(10000)
            ->assertSee('コース情報')->press('.show-logo')
            ->assertSee('コースID')
            ->assertSee('コース名')
            ->assertSee('運行情報')
            ->mouseover('.th-course-name')->pause(2000)
            ->click('.th-course-id')->pause(10000)
            ->click('.th-course-name')->pause(10000)
            ->click('.th-course-id')->pause(10000)
            ->click('tbody  tr:nth-child(1)  td:nth-child(6)')->pause(7000)
            ->assertSee('コース詳細')
            ->assertSee('基本情報')
            ->assertSee('コースID')
            ->assertSee('コース名')
            ->assertSee('専任')
            ->visit('/data-management/list-course/index')->pause(6000);
    }

    private function actionFromCourse($browser)
    {
        $browser->visit('data-management/list-course/create')
            ->pause(4000)
            ->click('button.btn.btn-color-active.btn-save.btn-secondary.rounded-pill')->pause(1000)
            ->assertSee('入力されていない項目があります。')
            ->pause(10000)
            // check course > 5
            ->typeSlowly('#input-course-id', '08000' . rand(10, 99))
            ->typeSlowly('#input-course-name', Str::random(6) . ' ' . Str::random(4))
            ->press('label[for="input-start-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(9)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(9)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(1)')
            ->press('label[for="input-end-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(19)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(19)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(1)')
            ->press('label[for="input-break-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(2)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(2)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(1)')->pause(1000)
            ->mouseover('.list-select.show .select-option:nth-child(2)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(3)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(3)')
            ->click('#input-fatigue')->pause(1000)
            ->type('#input-fatigue', 2)
            ->type('#input-start-date', '2022-10-01')
//            ->type('#input-end-date', '2023-10-01')
            ->pause(2000)
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')->pause(2000)
            ->press('.btn-save')->pause(1000)
            ->assertSee('コースIDは半角数字5桁で入力してください。')
            ->pause(2000)
            ->typeSlowly('#input-course-id', '99999')
            ->typeSlowly('#input-course-name', Str::random(20) . ' ' . Str::random(20)) // check > 20 char
            ->press('.btn-save')
            ->pause(2000)
            ->typeSlowly('#input-course-name', Str::random(4) . ' ' . Str::random(6)) // check > 20 char
             // check group 1 char
            ->press('label[for="input-group-code"] + div.input-group > .select-multiple')
            ->press('label[for="input-group-code"] + div.input-group > .select-multiple .list-select.show .select-option:nth-child(1)')->pause(2000)
            ->click('.btn-save')
            ->pause(1000)
            ->assertSee('グループ項目はアルファベット両方を選択してください。')
            ->press('label[for="input-group-code"] + div.input-group > .select-multiple')
            ->press('label[for="input-group-code"] + div.input-group > .select-multiple .list-select.show .select-option:nth-child(2)')->pause(2000)

            ->pause(10000)
            //check time start_date and end_date
            ->press('label[for="input-start-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(9)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(8)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(2)')
            ->press('label[for="input-end-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(9)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(6)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(2)')
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')->pause(2000)
            ->press('.btn-save')->pause(1000)
            ->assertSee('開始時刻は終了時刻より前に設定してください。')
//            ->scrollIntoView('')

             //check time breack


            ->press('label[for="input-end-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(9)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(9)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(1)')

            ->press('label[for="input-break-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(10)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(10)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(3)')->pause(1000)
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')->pause(2000)
            ->press('.btn-save')->pause(1000)
            ->assertSee('休憩時間が長すぎます。')
            ->pause(2000)
            ->scrollIntoView('#input-fatigue')
            ->pause(2000)

            // resert  input true
            ->press('label[for="input-end-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(19)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(19)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(2)')

            ->press('label[for="input-break-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(2)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(2)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(1)')->pause(1000)

            // check time end work and start work
            ->type('#input-end-date', '2021-10-01')
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')
            ->press('.btn-save')->pause(1000)
            ->type('#input-end-date', '2022-11-25')



            //more > 10000 char
            ->scrollIntoView('#input-notes')
            ->type('#input-notes',Str::random(1001))
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')->pause(2000)
            ->press('.btn-save')->pause(1000)
//            ->assertSee('メモは1000文字以内で入力してください。')
            ->scrollIntoView('#input-notes')
            ->type('#input-notes',' ')
            ->type('#input-notes','Test IT')
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')
            ->press('.btn-save')
            ->pause(20000)

            // sort by seelisst
            ->press('.th-id')
            ->pause(5000)
            ->press('.th-id')->pause(3000)
            ->assertSee('99999')
            ->click('tbody > tr:nth-child(1) > td:nth-child(6) > i')->pause(5000)
            ->click('.btn-edit')->pause(4000) // click edit
            ->assertSee('コース編集')
            ->assertSee('基本情報')
            ->refresh()

            // edit
            ->pause(2000)
            ->check('div.zone-form__body > div:nth-child(1) > div > div:nth-child(1) > div > label')
            ->pause(2000)
            ->typeSlowly('#input-course-name', 'gactest123')

            ->press('label[for="input-group-code"] + div.input-group > .select-multiple')
            ->press('label[for="input-group-code"] + div.input-group > .select-multiple .list-select.show .select-option:nth-child(1) .list-option > li:nth-child(2) .item-option')->pause(2000)
            ->press('label[for="input-group-code"] + div.input-group > .select-multiple .list-select.show .select-option:nth-child(2) .list-option > li:nth-child(2) .item-option')->pause(2000)


            ->press('label[for="input-start-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(5)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(5)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(1)')
            ->press('label[for="input-end-time"] + div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(19)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(20)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(1)')


//            ->press('label[for="input-break-time"] + div > .select-multiple > input')
            ->press('div.zone-form__body > div:nth-child(6) > div > div:nth-child(1) > div > div > input')
            ->waitFor('.list-select.show')
            ->mouseover('.list-select.show .select-option:nth-child(1)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(2)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(1)')
            ->press('.list-select.show .select-option:nth-child(1) .list-option li:nth-child(2)')->pause(1000)
            ->mouseover('.list-select.show .select-option:nth-child(2)')
            ->scrollIntoView('#input-fatigue')
            ->click('#input-fatigue')->pause(1000)
            ->type('#input-fatigue', 1)
//            ->type('#input-start-date', '2022-08-23')
            ->type('#input-end-date', '2023-10-01')
            ->type('#input-notes','Test course IT Test course IT が表示される')
            ->scrollIntoView('#input-course-name')->pause(2000)
            ->scrollIntoView('.btn-save')->pause(2000)
            ->press('.btn-save')
            ->pause(20000)

            ->press('.th-course-id')
            ->pause(5000)
            ->press('.th-course-id')
            ->pause(5000)
            ->assertSee('gactest123')
            // xóa
            ->click('tbody > tr:nth-child(1) > td:nth-child(7) > i')
            ->pause(2000)
            ->click('#modal-delete___BV_modal_footer_ > button.btn.btn-primary')
            ->pause(6000)
            ->assertDontSee('99999')

            #__BVID__1629 > tbody > tr:nth-child(1) > td:nth-child(6) > i


            ->pause(2000000000000);


    }
    private function loginAdmin($browser)
    {
        $browser->visit('/login')
            ->pause(10000)
            ->typeSlowly("#user_id", '1122')
            ->typeSlowly('#password', 'abc12345678')
            ->press(".login-btn")->pause(5000);
    }


}
