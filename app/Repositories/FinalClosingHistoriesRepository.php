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


}
