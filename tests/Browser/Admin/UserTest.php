<?php

namespace Tests\Browser\Admin;

use App\Models\Driver;
use App\Models\User;
use Facebook\WebDriver\WebDriverBy;
use Helper\Common;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class UserTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        User::where('user_code','4444')->delete();


        $this->browse(function ($browser) {
            $browser->maximize();
            $browser->pause(5000);
            $this->loginAdmin($browser);
            $this->createUser($browser);
            $this->listUser($browser);
            $this->editUser($browser);
        });
    }

    private function loginAdmin($browser)
    {
        $browser->visit('/login');
        $browser->type("#user_id",'')->pause(2000);
        $browser->type("#user_id",'1122')->pause(2000);
        $browser->type("#password",'')->pause(2000);
        $browser->type('#password', 'abc12345678')->pause(2000)
            ->click(".login-btn")->pause(10000)
            ->pause(7000)
            ->assertSee('データ管理')
            ->assertSee('TOSHIN');
    }
    private function createUser($browser){
        $browser->pause(3000);
        $browser->mouseover('.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2)')->pause(6000)
            ->click('.app-layout__navbar > div > div.zone-navigation > div.show-menu > ul > li:nth-child(2) > ul > li:nth-child(3)')->pause(5000)
            ->assertSee('ユーザー情報')->pause(2000)
            ->assertSee('ユーザーID')->pause(2000)
            ->assertSee('ユーザー名')->pause(2000)
            ->assertSee('ユーザー権限')->pause(2000)
            ->click('div.container > div > div.list-user__header > div.row > div:nth-child(2) > div > button')->pause(3000)
            ->click('.btn-save')->pause(1000)
            ->assertSee('入力または選択されていない項目があります。')
            ->pause(1000)


            ->type('#input-user-id','1122')->pause(2000)
            ->type('#input-user-name','1122')->pause(2000)
            ->click('#input-user-authority')->pause(2000)
            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
            ->type('#input-user-password','abc123456789')->pause(2000)
            ->click('.btn-save')->pause(2000)
            ->assertSee('指定のユーザーIDは既に使用されています。')->pause(1000) // diuplucate

            ->type('#input-user-id','1124442')->pause(2000)
            ->type('#input-user-name','1122')->pause(2000)
            ->click('#input-user-authority')->pause(2000)
            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
            ->type('#input-user-password','abc123456789')->pause(2000)
            ->click('.btn-save')->pause(1000)
            ->assertSee('ユーザーIDは半角数字4桁以内で入力してください。')->pause(1000) //more 4 char

            ->type('#input-user-id','4444')->pause(2000)
            ->type('#input-user-name','Ten duoc phep vuot qua 20 ki tu')->pause(2000)
            ->click('#input-user-authority')->pause(2000)
            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
            ->type('#input-user-password','abc123456789')->pause(2000)
            ->click('.btn-save')->pause(2000)
            ->assertSee('20文字以下にしてください。')->pause(1000) // name 20

            ->type('#input-user-id','4444')->pause(2000)
            ->type('#input-user-name','Linhnt')->pause(2000)
            ->click('#input-user-authority')->pause(2000)
            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
            ->type('#input-user-password','abc123456789999999999999')->pause(2000)
            ->click('.btn-save')->pause(1000)
            ->assertSee('パスワードは８文字以上16文字以内半角英数字で入力してください')->pause(1000) // pass 20

            ->type('#input-user-id','4444')->pause(2000)
            ->type('#input-user-name','Linhnt')->pause(2000)
            ->click('#input-user-authority')->pause(2000)
            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
            ->type('#input-user-password','abc12345678')->pause(2000)
            ->click('.btn-save')->pause(4000)
            ->assertSee('4444')->pause(5000); // name 20

    }
    private function listUser($browser)
    {
        $browser->pause(3000)

            ->click('thead > tr > th:nth-child(2) ')->pause(3000)
            ->click('thead > tr > th:nth-child(3) ')->pause(3000)
            ->click('tbody > tr:nth-child(1) > td:nth-child(4) > i')->pause(5000)
            ->assertSee('ユーザー情報')
            ->assertSee('ユーザー詳細')
            ->assertSee('ユーザーID')
            ->assertSee('ユーザー名')
            ->assertSee('ユーザー権限')
            ->assertSee('パスワード')
            ->visit('/data-management/list-user')
            ->pause(5000)
            ->click('tbody > tr:nth-child(2) > td:nth-child(5) > i')->pause(3000)
            ->click('#modal-delete___BV_modal_footer_ > button.btn.btn-primary')->pause(3000)
            ->assertDontSee('2233')->pause(3000);
    }
    private function editUser($browser){
        $browser->click('tbody > tr:nth-child(2) > td:nth-child(4) > i')->pause(5000)
            ->click(".btn-edit")->pause(2000)
            ->type('#input-user-name','')->pause(2000)
            ->type('#input-user-name','NguyenLinh')->pause(2000)
            ->click('#input-user-authority > option:nth-child(2)')->pause(2000)
            ->type('#input-user-password','11111111')->pause(2000)
            ->click('.btn-save')->pause(2000)
            ->assertSee('ユーザーを作成しました。')->pause(1000)
            ->assertSee('NguyenLinh')->pause(600000);
    }
}
