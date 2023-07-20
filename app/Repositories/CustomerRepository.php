<?php
/**
 * Created by VeHo.
 * Year: 2023-07-20
 */

namespace Repository;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param Customer $model
       */

    public function model()
    {
        return Customer::class;
    }


}
