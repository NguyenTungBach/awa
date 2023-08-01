<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace Repository;

use App\Models\CashOut;
use App\Models\CashOutHistory;
use App\Repositories\Contracts\CashOutRepositoryInterface;
use Helper\ResponseService;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use App\Models\FinalClosingHistories;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class CashOutRepository extends BaseRepository implements CashOutRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
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
        $input['note'] = Arr::get($input, 'note', NULL);

        $cashOut = CashOut::create([
            'driver_id' => $input['driver_id'],
            'cash_out' => $input['cash_out'],
            'payment_method' => $input['payment_method'],
            'payment_date' => $input['payment_date'],
            'note' => $input['note'],
        ]);

        return $cashOut;
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
            $data[$key]['id'] = $value->id;
            $data[$key]['driver_id'] = $value->driver_id;
            $data[$key]['payment_date'] = date('Y/m/d', strtotime($value->payment_date));
            $data[$key]['cash_out'] = $value->cash_out;
            $data[$key]['payment_method'] = __('cash_outs.payment_method_lang.'.$value->payment_method);
            $data[$key]['note'] = empty($value->note) ? '' : $value->note;
        }
        $result = $data;

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
                unset($input['_method'], $input['driver_id']);
                $result = CashOutRepository::update($input, $input['cash_out_id']);
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
                unset($input['_method'], $input['driver_id']);
                $result = CashOutRepository::find($input['cash_out_id'])->delete();
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
}
