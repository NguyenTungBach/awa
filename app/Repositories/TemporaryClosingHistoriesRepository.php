<?php
namespace Repository;

use App\Http\Resources\BaseResource;
use App\Http\Resources\TemporaryClosingHistoriesResource;
use App\Models\FinalClosingHistories;
use App\Models\TemporaryClosingHistories;
use App\Repositories\Contracts\TemporaryClosingHistoriesRepositoryInterface;
use Illuminate\Http\Response;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class TemporaryClosingHistoriesRepository extends BaseRepository implements TemporaryClosingHistoriesRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
       * Instantiate model
       *
       * @param TemporaryClosingHistories $model
       */

    public function model()
    {
        return TemporaryClosingHistories::class;
    }

    public function createTemporaryClosing($input)
    {
        $result = TemporaryClosingHistories::create([
            'date' => $input['date'],
            'month_year' => $input['month_year'],
        ]);

        return $result;
    }

    public function getAll($input)
    {
        $temporaries = TemporaryClosingHistories::orderBy('month_year', 'desc');

        if(!empty($input['month_year'])) {
            $temporaries = $temporaries->where('month_year', $input['month_year']);
        }
        $result = $temporaries->get();

        return $result;
    }

    public function getDetail($id)
    {
        $result = TemporaryClosingHistoriesRepository::find($id);

        return $result;
    }

    public function deleteTemporaryClosing($id)
    {
        $result = TemporaryClosingHistoriesRepository::find($id)->delete();

        return $result;
    }

    // Bach add
    public function checkTemporary($request)
    {
        // Kiểm tra xem tháng này đã final closing chưa nếu có rồi thì checkTemporary là false
        $resultFinalClosingHistories = FinalClosingHistories::where('month_year',$request->month_year)->first();
        if ($resultFinalClosingHistories){
            return $this->responseJson(Response::HTTP_OK, new BaseResource(['checkTemporary'=>false]), SUCCESS);
        }

//        // Kiểm tra xem có Temporary trong tháng này không
//        $resultTemporaryClosingHistories = TemporaryClosingHistories::where("month_year",$request->month_year)->first();
//        if ($resultTemporaryClosingHistories){
//            return $this->responseJson(Response::HTTP_OK, new BaseResource(['checkTemporary'=>true]), SUCCESS);
//        }
        return $this->responseJson(Response::HTTP_OK, new BaseResource(['checkTemporary'=>true]), SUCCESS);
    }
}
