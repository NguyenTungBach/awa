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
use Illuminate\Support\Arr;

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

    public function createCustomer($input)
    {
        $note = Arr::get($input, 'note', NULL);
        $status = Arr::get($input, 'status', NULL);

        $customer = Customer::create([
            'customer_code' => $input['customer_code'],
            'customer_name' => $input['customer_name'],
            'closing_date' => $input['closing_date'],
            'person_charge' => $input['person_charge'],
            'post_code' => $input['post_code'],
            'address' => $input['address'],
            'phone' => $input['phone'],
            'note' => $note,
            'status' => $status,
        ]);

        return $customer;
    }

    public function getAll($input)
    {
        $input['order_by'] = Arr::get($input, 'order_by', 'customer_code');
        $input['sort_by'] = Arr::get($input, 'sort_by', 'desc');

        $data = [];
        $customers = Customer::orderBy($input['order_by'], $input['sort_by']);
        $customers = $customers->get();

        foreach ($customers as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['customer_code'] = $value->customer_code;
            $data[$key]['customer_name'] = $value->customer_name;
            $data[$key]['closing_date'] = $value->closing_date;
            $data[$key]['closing_date'] = __('customers.closing_date_lang.' . $value->closing_date);
        }
        $result = $data;

        return $result ?? [];
    }

    public function getDetail($id)
    {
        $result = CustomerRepository::find($id);
        $result['closing_date'] = __('customers.closing_date_lang.' . $result->closing_date);

        return $result;
    }

    public function updateCustomer($input, $id)
    {
        $input['note'] = Arr::get($input, 'note', NULL);
        $input['status'] = Arr::get($input, 'status', NULL);
        $result = CustomerRepository::update($input, $id);

        return $result;
    }

    public function deleteCustomer($id)
    {
        $result = CustomerRepository::find($id)->delete();

        return $result;
    }
}
