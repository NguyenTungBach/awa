<?php
/**
 * Created by VeHo.
 * Year: 2023-07-20
 */

namespace Repository;

use App\Models\Customer;
use App\Models\Course;
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
        if (!empty($input['phone'])) {
            $input['phone'] = str_replace('-', '', $input['phone']);
        }

        $customer = Customer::create([
            'customer_code' => $input['customer_code'],
            'customer_name' => $input['customer_name'],
            'closing_date' => $input['closing_date'],
            'person_charge' => $input['person_charge'],
            'tax' => $input['tax'],
            'post_code' => $input['post_code'],
            'address' => $input['address'],
            'phone' => $input['phone'],
            'note' => $note,
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
        $result['tax_value'] = $result->tax;
        $result['tax'] = trans('customers.tax.' . $result->tax);

        return $result;
    }

    public function updateCustomer($input, $id)
    {
        if (!empty($input['phone'])) {
            $input['phone'] = str_replace('-', '', $input['phone']);
        }
        $result = CustomerRepository::update($input, $id);

        return $result;
    }

    public function deleteCustomer($id)
    {
        $check = $this->checkCustomer($id);
        if ($check) {
            return false;
        }
        $result = CustomerRepository::find($id)->delete();

        return $result;
    }

    public function checkCustomer($id)
    {
        $arrCustomerId = Course::get()->pluck('customer_id')->toArray();
        $result = in_array($id, $arrCustomerId);

        return $result;
    }
}
