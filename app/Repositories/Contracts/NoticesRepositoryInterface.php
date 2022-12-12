<?php
/**
 * Created by VeHo.
 * Year: 2022-04-08
 */

namespace App\Repositories\Contracts;


interface NoticesRepositoryInterface extends BaseRepositoryInterface
{
    public function noticeList($request);
}
