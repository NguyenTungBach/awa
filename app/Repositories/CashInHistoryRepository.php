<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace Repository;

use App\Models\CashInHistory;
use App\Repositories\Contracts\CashInHistoryRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CashInHistoryRepository extends BaseRepository implements CashInHistoryRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param CashInHistory $model
       */

    public function model()
    {
        return CashInHistory::class;
    }


}
