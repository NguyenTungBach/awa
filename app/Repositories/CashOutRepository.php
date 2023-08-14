<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\CashOut;
use App\Models\CashOutHistory;
use App\Models\DriverCourse;
use App\Repositories\Contracts\CashOutRepositoryInterface;
use App\Repositories\Contracts\CashOutStatisticalRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use App\Models\FinalClosingHistories;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashOutRepository extends BaseRepository implements CashOutRepositoryInterface, CashOutStatisticalRepositoryInterface
{
    public function __construct(
        Application $app,
        CashOutStatisticalRepositoryInterface $cashOutStatisticalRepository
    ){
        parent::__construct($app);
        $this->cashOutStatisticalRepository = $cashOutStatisticalRepository;
    }

    /**
     * Instantiate model
     *
     * @param CashOut $model
     */

    public function model()
    {
        return CashOut::class;
    }

    public function createCashOutByDriver($input)
    {
        try {
            DB::beginTransaction();
            $cashOut = [];
            $checkDriverId = $this->checkExistsDriverCourse($input['driver_id'], $input['payment_date']);
            if ($checkDriverId) {
                $input['note'] = Arr::get($input, 'note', NULL);

                $cashOut = CashOut::create([
                    'driver_id' => $input['driver_id'],
                    'cash_out' => $input['cash_out'],
                    'payment_method' => $input['payment_method'],
                    'payment_date' => $input['payment_date'],
                    'note' => $input['note'],
                ]);
    
                unset($input['payment_method'], $input['note']);
                $cashOutCreate = $this->cashOutStatisticalRepository->updateCashOutStatisticalByCashOut($input);
            }
            DB::commit();

            return $cashOut;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }
    }

    public function getAllCashOutByDriver($input)
    {
        $input['filter_month'] = Arr::get($input, 'filter_month', date('Y-m', strtotime(now())));
        $input['year'] = date('Y', strtotime($input['filter_month']));
        $input['month'] = date('m', strtotime($input['filter_month']));
        $data = [];
        $cashOuts = CashOut::where('driver_id', $input['driver_id'])
                    ->whereYear('payment_date', '=', $input['year'])
                    ->whereMonth('payment_date', '=', $input['month'])
                    ->get();

        foreach ($cashOuts as $key => $value) {
            $data['list_cash_out'][$key]['id'] = $value->id;
            $data['list_cash_out'][$key]['driver_id'] = $value->driver_id;
            $data['list_cash_out'][$key]['payment_date'] = date('Y/m/d', strtotime($value->payment_date));
            $data['list_cash_out'][$key]['cash_out'] = $value->cash_out;
            $data['list_cash_out'][$key]['payment_method'] = __('cash_outs.payment_method_lang.'.$value->payment_method);
            $data['list_cash_out'][$key]['note'] = empty($value->note) ? '' : $value->note;
        }
        $result = ['data_list_cash_out'=> $data, 'total_cash_out_month' =>$cashOuts->sum('cash_out')];

        return $result;
    }

    public function getDetailCashOutByDriver($input)
    {
        $result = CashOut::where('driver_id', $input['driver_id'])->where('id', $input['cash_out_id'])->first();

        if (!empty($result)) {
            $result['id'] = $result->id;
            $result['driver_id'] = $result->driver_id;
            $result['payment_date'] = date('Y年m月d日', strtotime($result->payment_date));
            $result['payment_method'] = __('cash_outs.payment_method_lang.'.$result->payment_method);
            $result['note'] = empty($result->note) ? '' : $result->note;
        }

        return $result;
    }

    public function updateCashOutByDriver($input)
    {
        try {
            DB::beginTransaction();
            $result = [];
            $cashOut = CashOut::where('driver_id', $input['driver_id'])->where('id', $input['cash_out_id'])->first();
            if (!empty($cashOut)) {
                $cashOutHistory = $this->createCashOutHistory($cashOut);
                $driverId = $input['driver_id'];
                unset($input['_method'], $input['driver_id']);
                $result = CashOutRepository::update($input, $input['cash_out_id']);

                unset($input['payment_method'], $input['note']);
                $input['driver_id'] = $driverId;
                $cashOutUpdate = $this->cashOutStatisticalRepository->updateCashOutStatisticalByCashOut($input);
            }
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }
    }

    public function deleteCashOutByDriver($input)
    {
        try {
            DB::beginTransaction();
            $result = [];
            $cashOut = CashOut::where('driver_id', $input['driver_id'])->where('id', $input['cash_out_id'])->first();
            if (!empty($cashOut)) {
                $cashOutHistory = $this->createCashOutHistory($cashOut);
                $driverId = $input['driver_id'];
                unset($input['_method'], $input['driver_id']);
                $result = CashOutRepository::find($input['cash_out_id'])->delete();

                $input['driver_id'] = $driverId;
                $input['payment_date'] = $cashOut->payment_date;
                $cashOutDelete = $this->cashOutStatisticalRepository->updateCashOutStatisticalByCashOut($input);
            }
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }
    }

    public function createCashOutHistory($data)
    {
        $data['type'] = config('cash_outs.type.'.Route::getCurrentRoute()->getActionMethod());
        $create = CashOutHistory::create([
            'driver_id' => $data['driver_id'],
            'cash_out_id' => $data['id'],
            'type' => $data['type'],
            'cash_out' => $data['cash_out'],
            'payment_method' => $data['payment_method'],
            'payment_date' => $data['payment_date'],
            'note' => $data['note'],
        ]);

        return $create;
    }

    public function checkExistsDriverCourse($driverId, $date)
    {
        $result = false;
        $driverCourses = DriverCourse::where('driver_id', $driverId)->where('date', '<=', $date)->get();
        if (!($driverCourses->isEmpty())) {
            $result = true;
        }

        return $result;
    }
}
