<?php
/**
 * Created by VeHo.
 * Year: 2023-07-25
 */

namespace Repository;

use App\Models\FinalClosingHistories;
use App\Repositories\Contracts\FinalClosingHistoriesRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class FinalClosingHistoriesRepository extends BaseRepository implements FinalClosingHistoriesRepositoryInterface
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

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
        $result = FinalClosingHistories::create([
            'date' => $input['date'],
            'month_year' => $input['month_year'],
            'type' => $input['type'],
        ]);

        return $result;
    }

    public function getAll($input)
    {
        $finals = FinalClosingHistories::orderBy('month_year', 'desc');

        if(!empty($input['month_year'])) {
            $finals = $finals->where('month_year', $input['month_year']);
        }

        if(!empty($input['type'])) {
            $finals = $finals->where('type', $input['type']);
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
}