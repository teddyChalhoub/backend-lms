<?php

namespace App\Http\Requests;

use App\Rules\SectionRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AddSectionRequest extends FormRequest
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
            'name' => ['required',new SectionRule($this->grade_id)],
            'max_students' => ['required'],
            'grade_id' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'required'=> ':attribute must be provided',
            'unique'=>':attribute already exists'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'max_students' => 'Maximum Number Of Students',
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
        $errors = collect($validator->errors());
        $errors = $errors->collapse();

        if($this->wantsJson()) {

            $response = response()->json([
                'success' => false,
                'message' => 'Ops! Some errors occurred',
                'errors' => $errors
            ]);
        }

        throw (new ValidationException($validator, $response));
    }
}
