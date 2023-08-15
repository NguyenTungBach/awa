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

        $rules = [
            'customer_id' => [
                'required',
                Rule::in($arrCustomerId)
            ],
            'course_name' => [
                'required',
                'string',
                'max:20',
                'unique:courses,course_name,NULL,id,deleted_at,NULL',
            ],
            'ship_date' => [
                'required',
                'date_format:Y-m-d',
                new CourseRule(__('courses.ship_date')),
            ],
            'start_date' => [
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.start_date')),
                new CompareHours($this->get('end_date')),
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
            'ship_fee' => 'required|numeric',
            'associate_company_fee' => 'nullable|numeric',
            'expressway_fee' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'meal_fee' => 'nullable|numeric',
            'note' => 'nullable|string|max:1000',
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

        $rules = [
            'customer_id' => [
                'sometimes',
                'required',
                Rule::in($arrCustomerId)
            ],
            'course_name' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('courses')->ignore($course->id),
            ],
            'ship_date' => [
                'sometimes',
                'required',
                'date_format:Y-m-d',
                new CheckDriverCourseExists(__('courses.ship_date'))
            ],
            'start_date' => [
                'sometimes',
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.start_date')),
                new CompareHours($this->get('end_date')),
            ],
            'end_date' => [
                'sometimes',
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.end_date'))
            ],
            'break_time' => [
                'sometimes',
                'required',
                'date_format:H:i',
                new TimeRule(__('courses.break_time'))
            ],
            'departure_place' => 'sometimes|required|string|max:20',
            'arrival_place' => 'sometimes|required|string|max:20',
            'ship_fee' => 'sometimes|required|max:15',
            'associate_company_fee' => 'nullable|max:15',
            'expressway_fee' => 'nullable|max:15',
            'commission' => 'nullable|max:15',
            'meal_fee' => 'nullable|max:15',
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
            'customer_id.required' => __('validation.required', ['attribute' => __('courses.customer_id')]),
            'customer_id.in' => __('validation.in', ['attribute' => __('courses.customer_id')]),
            // course_name
            'course_name.required' => __('validation.required', ['attribute' => __('courses.course_name')]),
            'course_name.string' => __('validation.string', ['attribute' => __('courses.course_name')]),
            'course_name.max' => __('validation.max.string', ['attribute' => __('courses.course_name'), 'max' => 20]),
            'course_name.unique' => __('validation.unique', ['attribute' => __('courses.course_name')]),
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
            'file.required' => __('validation.required', ['attribute' => __('courses.file')]),
            'file.max' => __('validation.max.file', ['attribute' => __('courses.file'), 'max' => 3000]),
            'file.mimes' => __('validation.mimes', ['attribute' => __('courses.file'), 'values' => '.xlsx']),
        ];
    }
}
