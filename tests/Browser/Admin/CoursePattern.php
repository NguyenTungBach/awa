<?php

namespace Tests\Browser\Admin;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CoursePattern extends DuskTestCase
{
    use withFaker;

    public function testCoursePattern()
    {
        $this->browse(function ($browser) {

            $this->loginAdmin($browser);
            $browser->pause(4000);
            $this->list($browser);
            $browser->pause(2000);
            $this->editPattern($browser);
            $browser->pause(2000);

            $this->listCourse($browser);
            $browser->pause(2000);
            $this->createCourse($browser);
            $browser->pause(2000);
            $this->list($browser);

            $this->scrolDownAndUp($browser);
            $browser->pause(2000);

            $this->listCourse($browser);
            $this->deleteCourse($browser);
            $browser->pause(2000);
            $this->list($browser);
            $browser->pause(1000);
            $this->scrolDownAndUp($browser);

            $this->listCourse($browser);
            $browser->pause(2000);
            $this->createCourse($browser, true);
            $browser->pause(2000);
            $this->list($browser);
        });
    }

    private function deleteCourse($browser)
    {
        $element = 'tr:last-child td:nth-child(7)';

        $browser
            ->scrollIntoView($element)
            ->pause(3000)
            ->press($element)
            ->pause(3000)
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

    private function scrolDownAndUp($browser)
    {
        $browser
            ->pause(1000)
            ->scrollIntoView('tr:last-child td:last-child')
            ->pause(6000)
            ->scrollIntoView('tr:first-child')
            ->pause(4000)
        ;
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

    private function createCourse($browser, $flag = false)
    {
        $element = '.btn-color-sign-up';
        $browser
            ->mouseover($element)
            ->press($element)
            ->pause(1000)
            ->assertSee('コース新規登録');

        if ($flag) {
            $browser->press('.custom-checkbox label');
        }

        $browser
            ->typeSlowly('#input-course-id', $this->faker->randomNumber(5))

            ->typeSlowly('#input-course-name', $this->faker->firstName . ' ' . $this->faker->lastName)

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
            ->pause(1000)
            ->mouseover('.list-select.show .select-option:nth-child(2)')
            ->scrollIntoView('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(3)')
            ->press('.list-select.show .select-option:nth-child(2) .list-option li:nth-child(3)')

            ->select('label[for="input-fatigue"] + select', 2)

            ->type('#input-start-date', '2022-10-01')

            ->type('#input-end-date', '2023-10-01')
            ->pause(2000)

            ->press('.btn-save')
            ->pause(2000)
            ->assertSee('成功');

    }

    private function editPattern($browser)
    {
        $browser
            ->press('.btn-edit')
            ->pause(2000)
            ->assertSee('コース組み合わせ表')
            ->press('tr:first-child td:nth-child(4)')
            ->waitFor('#modal-edit')
            ->assertSee('コース組み合わせ表')
            ->pause(2000)
            ->press('.custom-radio:nth-child(2) label')
            ->pause(1000)
            ->press('#modal-edit .btn-save')
            ->pause(1500)
            ->press('.btn-save')
            ->pause(2000)
            ->assertSee('成功')
        ;
    }

    private function list($browser)
    {
        $element = 'ul.item-modules li:nth-child(2) ul.item-path li:nth-child(2) ul.item-child li:nth-child(1)';
            $browser
                ->mouseover('ul.item-modules > li:nth-child(2)')
                ->pause(1500)
                ->mouseover('ul.item-modules > li:nth-child(2) ul.item-path li:nth-child(2)')
                ->waitFor($element)
                ->click($element)
                ->pause(3000)
                ->assertSee('コース組み合わせ表');
    }

    private function loginAdmin($browser)
    {
        $browser->visit('/login')
            ->pause(10000)
            ->typeSlowly("#user_id",'1122')
            ->typeSlowly('#password', 'abc12345678')
            ->press(".login-btn")->pause(1000);
    }
}
