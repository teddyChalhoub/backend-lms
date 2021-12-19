<?php

namespace App\Http\Requests;

use App\Rules\SectionRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateSectionRequest extends FormRequest
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
        return [
            'name' => [ new SectionRule($this->grade_id)],
            'max_students' => [''],
            'grade_id' => [''],
        ];
    }

    public function messages()
    {
        return [
            'required'=> ':attribute must be provided',
            'unique'=>':attribute already exits'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'max_students' => 'Max Number Of Students',
            'grade_id' => 'Grade Id',

        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {

        if($this->wantsJson())
        {
            $response = response()->json([
                'success' => false,
                'message' => 'Ops! Some errors occurred',
                'errors' => $validator->errors()
            ]);
        }

        throw (new ValidationException($validator, $response));
    }
}
