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
use Illuminate\Validation\Rule;

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
            default:
                return [];
        }
    }

    public function getCustomRuleStore(){
        $arrCustomerId = Customer::get()->pluck('id');

        $rules = [
            'customer_id' => [
                'required',
                Rule::in($arrCustomerId)
            ],
            'course_name' => 'required|string|max:20',
            'ship_date' => [
                'required',
                'date_format:Y-m-d',
                new CourseRule(__('courses.ship_date')),
            ],
            'start_date' => [
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.start_date'))
            ],
            'end_date' => [
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.end_date'))
            ],
            'break_time' => [
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.break_time'))
            ],
            'departure_place' => 'required|string|max:20',
            'arrival_place' => 'required|string|max:20',
            'ship_fee' => 'required|max:15',
            'associate_company_fee' => 'nullable|max:15',
            'expressway_fee' => 'nullable|max:15',
            'commission' => 'nullable|max:15',
            'meal_fee' => 'nullable|max:15',
            'note' => 'nullable|string|max:1000',
            'status' => 'nullable',
        ];

        return $rules;
    }

    public function getCustomRuleIndex(){
        $arrCustomerId = Customer::get()->pluck('id');

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
            'order_by' => [
                'sometimes',
                Rule::in(['ship_date', 'course_name', 'customer_name', 'departure_place', 'arrival_place', 'ship_fee'])
            ],
            'sort_by' =>  [
                'sometimes',
                Rule::in(SORT_BY)
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // customer_id
            'customer_id.required' => __('validation.required', ['attribute' => __('courses.customer_id')]),
            'customer_id.in' => __('validation.in', ['attribute' => __('courses.customer_id')]),
            // course_name
            'course_name.required' => __('validation.required', ['attribute' => __('courses.course_name')]),
            'course_name.string' => __('validation.string', ['attribute' => __('courses.course_name')]),
            'course_name.max' => __('validation.max.string', ['attribute' => __('courses.course_name'), 'max' => 20]),
            // ship_date
            'ship_date.required' => __('validation.required', ['attribute' => __('courses.ship_date')]),
            'ship_date.date_format' => __('validation.date_format', ['attribute' => __('courses.ship_date'), 'format' => 'Y-m-d']),
            // start_date
            'start_date.required' => __('validation.required', ['attribute' => __('courses.start_date')]),
            'start_date.date_format' => __('validation.date_format', ['attribute' => __('courses.start_date'), 'format' => 'H:i']),
            // end_date
            'end_date.required' => __('validation.required', ['attribute' => __('courses.end_date')]),
            'end_date.date_format' => __('validation.date_format', ['attribute' => __('courses.end_date'), 'format' => 'H:i']),
            // break_time
            'break_time.required' => __('validation.required', ['attribute' => __('courses.break_time')]),
            'break_time.date_format' => __('validation.date_format', ['attribute' => __('courses.break_time'), 'format' => 'H:i']),
            // departure_place
            'departure_place.required' => __('validation.required', ['attribute' => __('courses.departure_place')]),
            'departure_place.string' => __('validation.string', ['attribute' => __('courses.departure_place')]),
            'departure_place.max' => __('validation.max.string', ['attribute' => __('courses.departure_place'), 'max' => 20]),
            // arrival_place
            'arrival_place.required' => __('validation.required', ['attribute' => __('courses.arrival_place')]),
            'arrival_place.string' => __('validation.string', ['attribute' => __('courses.arrival_place')]),
            'arrival_place.max' => __('validation.max.string', ['attribute' => __('courses.arrival_place'), 'max' => 20]),
            // ship_fee
            'ship_fee.required' => __('validation.required', ['attribute' => __('courses.ship_fee')]),
            // associate_company_fee
            // expressway_fee
            // commission
            // meal_fee
            // note
            'note.string' => __('validation.string', ['attribute' => __('courses.note')]),
            'note.max' => __('validation.max.string', ['attribute' => __('courses.note'), 'max' => 1000]),
        ];
    }
}
