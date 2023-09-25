<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Http\Requests;

use App\Rules\ShipDateRule;
use App\Rules\CourseRule;
use App\Rules\TimeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Validation\Rule;
use App\Rules\CompareHours;
use App\Rules\CheckDriverCourseExists;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (Route::getCurrentRoute()->getActionMethod()) {
            case 'store':
                return $this->getCustomRuleStore();
            case 'update':
                return $this->getCustomRuleUpdate();
            case 'index':
                return $this->getCustomRuleIndex();
            case 'export':
                return $this->getCustomRuleIndex();
            case 'import':
                return $this->getCustomRuleImport();
            default:
                return [];
        }
    }

    public function getCustomRuleStore(){
        $arrCustomerId = Customer::get()->pluck('id');
        $arrDriverId = Driver::get()->pluck('id');

        $rules = [
            'customer_id' => [
                'required',
                Rule::in($arrCustomerId)
            ],
            // 'course_name' => [
            //     'required',
            //     'string',
            //     'max:20',
            //     'unique:courses,course_name,NULL,id,deleted_at,NULL',
            // ],
            'driver_id' => [
                'required',
                Rule::in($arrDriverId)
            ],
            'vehicle_number' => 'nullable|numeric|digits_between:1,20',
            'ship_date' => [
                'required',
                'date_format:Y-m-d',
                new CourseRule(__('courses.ship_date')),
            ],
            'start_date' => [
                'nullable',
                'date_format:H:i',
                new TimeRule(__('courses.start_date')),
                // new CompareHours($this->get('end_date')),
            ],
            'end_date' => [
                'nullable',
                'date_format:H:i',
                new TimeRule(__('courses.end_date'))
            ],
            'break_time' => [
                'nullable',
                'date_format:H:i',
                new TimeRule(__('courses.break_time'))
            ],
            'departure_place' => 'required|string|max:20',
            'arrival_place' => 'required|string|max:20',
            'item_name' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[a-zA-Z0-9\sぁ-んァ-ン一-龥]+$/'
            ],
            'quantity' => 'nullable|numeric|digits_between:1,15',
            'price' => 'nullable|numeric|digits_between:1,15',
            'weight' => 'nullable|numeric|digits_between:1,15',
            'ship_fee' => 'nullable|numeric|digits_between:1,15',
            'associate_company_fee' => 'nullable|numeric|digits_between:1,15',
            'expressway_fee' => 'nullable|numeric|digits_between:1,15',
            'commission' => 'required|numeric|digits_between:1,15',
            'meal_fee' => 'required|numeric|digits_between:1,15',
            'note' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function getCustomRuleIndex(){
        $arrCustomerId = Customer::get()->pluck('id');
        $arrDriverId = Driver::get()->pluck('id');

        $rules = [
            'start_date_ship' => [
                'sometimes',
                'date_format:Y-m-d',
                new ShipDateRule($this->get('end_date_ship')),
            ],
            'end_date_ship' => [
                'sometimes',
                'date_format:Y-m-d',
            ],
            'customer_id' => [
                'sometimes',
                Rule::in($arrCustomerId)
            ],
            'driver_id' => [
                'sometimes',
                Rule::in($arrDriverId)
            ],
            'order_by' => [
                'sometimes',
                Rule::in(['ship_date', 'course_name', 'customer_name', 'departure_place', 'arrival_place', 'ship_fee', 'driver_name'])
            ],
            'sort_by' =>  [
                'sometimes',
                Rule::in(SORT_BY)
            ],
            'month_line' => [
                'sometimes',
                'date_format:Y-m',
            ]
        ];

        return $rules;
    }

    public function getCustomRuleUpdate(){
        $arrCustomerId = Customer::get()->pluck('id');
        $course = Course::find(request()->route('course'));
        $arrDriverId = Driver::get()->pluck('id');

        $rules = [
            'customer_id' => [
                'sometimes',
                'required',
                Rule::in($arrCustomerId)
            ],
            // 'course_name' => [
            //     'sometimes',
            //     'required',
            //     'string',
            //     'max:20',
            //     Rule::unique('courses')->ignore($course->id),
            // ],
            'driver_id' => [
                'sometimes',
                'required',
                Rule::in($arrDriverId)
            ],
            'vehicle_number' => 'nullable|numeric|digits_between:1,15',
            'ship_date' => [
                'sometimes',
                'required',
                'date_format:Y-m-d',
                new CheckDriverCourseExists(__('courses.ship_date'))
            ],
            'start_date' => [
                'nullable',
                'date_format:H:i',
                new TimeRule(__('courses.start_date')),
                // new CompareHours($this->get('end_date')),
            ],
            'end_date' => [
                'nullable',
                'date_format:H:i',
                new TimeRule(__('courses.end_date'))
            ],
            'break_time' => [
                'nullable',
                'date_format:H:i',
                new TimeRule(__('courses.break_time'))
            ],
            'departure_place' => 'sometimes|required|string|max:20',
            'arrival_place' => 'sometimes|required|string|max:20',
            'item_name' => 'nullable|string|max:20',
            'quantity' => 'nullable|numeric|digits_between:1,15',
            'price' => 'nullable|numeric|digits_between:1,15',
            'weight' => 'nullable|numeric|digits_between:1,15',
            'ship_fee' => 'nullable|numeric|digits_between:1,15',
            'associate_company_fee' => 'nullable|numeric|digits_between:1,15',
            'expressway_fee' => 'nullable|numeric|digits_between:1,15',
            'commission' => 'sometimes|required|numeric|digits_between:1,15',
            'meal_fee' => 'sometimes|required|numeric|digits_between:1,15',
            'note' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function getCustomRuleImport(){
        $rules = [
            'file' => [
                'required',
                'max:3000',
                'mimes:xlsx'
            ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // customer_id
            'customer_id.required' => __('validation.custom.required_custom'),
            'customer_id.in' => __('validation.in', ['attribute' => __('courses.customer_id')]),
            // driver_id
            'driver_id.required' => __('validation.custom.required_custom'),
            'driver_id.in' => __('validation.in', ['attribute' => __('courses.driver_id')]),
            // vehicle_number
            'vehicle_number.integer' => __('validation.integer', ['attribute' => __('courses.vehicle_number')]),
            'vehicle_number.max' => __('validation.max.string', ['attribute' => __('courses.vehicle_number')]),
            // ship_date
            'ship_date.required' => __('validation.custom.required_custom'),
            'ship_date.date_format' => __('validation.date_format', ['attribute' => __('courses.ship_date'), 'format' => 'Y-m-d']),
            // start_date
            'start_date.required' => __('validation.custom.required_custom'),
            'start_date.date_format' => __('validation.date_format', ['attribute' => __('courses.start_date'), 'format' => 'H:i']),
            // end_date
            'end_date.required' => __('validation.custom.required_custom'),
            'end_date.date_format' => __('validation.date_format', ['attribute' => __('courses.end_date'), 'format' => 'H:i']),
            // break_time
            'break_time.required' => __('validation.custom.required_custom'),
            'break_time.date_format' => __('validation.date_format', ['attribute' => __('courses.break_time'), 'format' => 'H:i']),
            // departure_place
            'departure_place.required' => __('validation.custom.required_custom'),
            'departure_place.string' => __('validation.string', ['attribute' => __('courses.departure_place')]),
            'departure_place.max' => __('validation.max.string', ['attribute' => __('courses.departure_place'), 'max' => 20]),
            // arrival_place
            'arrival_place.required' => __('validation.custom.required_custom'),
            'arrival_place.string' => __('validation.string', ['attribute' => __('courses.arrival_place')]),
            'arrival_place.max' => __('validation.max.string', ['attribute' => __('courses.arrival_place'), 'max' => 20]),
            // item_name
            'item_name.required' => __('validation.custom.required_custom'),
            'item_name.string' => __('validation.string', ['attribute' => __('courses.item_name')]),
            'item_name.max' => __('validation.max.string', ['attribute' => __('courses.item_name'), 'max' => 20]),
            // quantity
            'quantity.numeric' => __('validation.numeric', ['attribute' => __('courses.quantity')]),
            'quantity.max' => __('validation.max.string', ['attribute' => __('courses.quantity'), 'max' => 15]),
            // price
            'price.numeric' => __('validation.numeric', ['attribute' => __('courses.price')]),
            'price.max' => __('validation.max.string', ['attribute' => __('courses.price'), 'max' => 15]),
            // weight
            'weight.numeric' => __('validation.numeric', ['attribute' => __('courses.weight')]),
            'weight.max' => __('validation.max.string', ['attribute' => __('courses.weight'), 'max' => 15]),
            // ship_fee
            'ship_fee.required' => __('validation.custom.required_custom'),
            'ship_fee.numeric' => __('validation.numeric', ['attribute' => __('courses.ship_fee')]),
            'ship_fee.max' => __('validation.max.string', ['attribute' => __('courses.ship_fee'), 'max' => 15]),
            // associate_company_fee
            'associate_company_fee.numeric' => __('validation.numeric', ['attribute' => __('courses.associate_company_fee')]),
            'associate_company_fee.max' => __('validation.max.string', ['attribute' => __('courses.associate_company_fee'), 'max' => 15]),
            // expressway_fee
            'expressway_fee.numeric' => __('validation.numeric', ['attribute' => __('courses.expressway_fee'), 'max' => 15]),
            'expressway_fee.max' => __('validation.max.string', ['attribute' => __('courses.expressway_fee'), 'max' => 15]),
            // commission
            'commission.numeric' => __('validation.numeric', ['attribute' => __('courses.commission'), 'max' => 15]),
            'commission.max' => __('validation.max.string', ['attribute' => __('courses.commission'), 'max' => 15]),
            // meal_fee
            'meal_fee.numeric' => __('validation.numeric', ['attribute' => __('courses.meal_fee'), 'max' => 15]),
            'meal_fee.max' => __('validation.max.string', ['attribute' => __('courses.meal_fee'), 'max' => 15]),
            // note
            'note.string' => __('validation.string', ['attribute' => __('courses.note')]),
            'note.max' => __('validation.max.string', ['attribute' => __('courses.note'), 'max' => 1000]),
            // file
            'file.required' => __('validation.custom.required_custom'),
            'file.max' => __('validation.max.file', ['attribute' => __('courses.file'), 'max' => 3000]),
            'file.mimes' => __('validation.mimes', ['attribute' => __('courses.file'), 'values' => '.xlsx']),
        ];
    }
}
