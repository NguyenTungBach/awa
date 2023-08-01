<?php
/**
 * Created by VeHo.
 * Year: 2023-08-01
 */

namespace Repository;

use App\Models\CashInStatical;
use App\Repositories\Contracts\CashInStaticalRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CashInStaticalRepository extends BaseRepository implements CashInStaticalRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param CashInStatical $model
       */

    public function model()
    {
        return CashInStatical::class;
    }


}
