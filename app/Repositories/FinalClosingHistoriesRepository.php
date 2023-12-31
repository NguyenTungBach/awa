<?php
/**
 * Created by VeHo.
 * Year: 2023-07-25
 */

namespace Repository;

use App\Http\Resources\FinalClosingHistoriesResource;
use App\Models\FinalClosingHistories;
use App\Models\TemporaryClosingHistories;
use App\Repositories\Contracts\FinalClosingHistoriesRepositoryInterface;
use Illuminate\Http\Response;
use Repository\DriverRepository;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class FinalClosingHistoriesRepository extends BaseRepository implements FinalClosingHistoriesRepositoryInterface
{
    public function __construct(Application $app, CashInStaticalRepository $cashInStaticalRepository)
    {
        parent::__construct($app);
        $this->cashInStaticalRepository = $cashInStaticalRepository;
    }

    /**
       * Instantiate model
       *
       * @param FinalClosingHistories $model
       */

    public function model()
    {
        return FinalClosingHistories::class;
    }

    public function createFinalClosing($input)
    {
        $driverIds = DriverRepository::listDriverIdsByFinal($input);
        $driverIds = empty($driverIds) ? NULL : json_encode($driverIds);

        $result = FinalClosingHistories::create([
            'date' => $input['date'],
            'month_year' => $input['month_year'],
            'driver_ids' => $driverIds,
        ]);

        // Bach cập nhật lại Temp
        $this->cashInStaticalRepository->cashInStaticalTemp($input);

        return $result;
    }

    public function getAll($input)
    {
        $finals = FinalClosingHistories::orderBy('month_year', 'desc');

        if(!empty($input['month_year'])) {
            $finals = $finals->where('month_year', $input['month_year']);
        }
        $result = $finals->get();

        return $result;
    }

    public function getDetail($id)
    {
        $result = FinalClosingHistoriesRepository::find($id);

        return $result;
    }

    public function deleteFinalClosing($id)
    {
        $result = FinalClosingHistoriesRepository::find($id)->delete();

        return $result;
    }

    public function checkFinalClosing($request)
    {
        $resultCashIn = TemporaryClosingHistories::where('month_year',$request->month_year)->first();
        $resultFinal = FinalClosingHistories::where('month_year',$request->month_year)->first();

        if ($resultCashIn && !$resultFinal){
            return $this->responseJson(Response::HTTP_OK, new FinalClosingHistoriesResource(['checkFinalClosing'=>false]), SUCCESS);
        }

        if ($resultFinal){
            return $this->responseJson(Response::HTTP_OK, new FinalClosingHistoriesResource(['checkFinalClosing'=>true]), SUCCESS);
        }
        return $this->responseJson(Response::HTTP_OK, new FinalClosingHistoriesResource(['checkFinalClosing'=>true]), SUCCESS);
    }
}
