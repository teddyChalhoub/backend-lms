<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreStudentRequest extends FormRequest
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
                'firstname' => ['required','max:255'],
                'lastname'=>['required'],
                'email'=>['unique:students','required','regex:/(.+)@(.+)\.(.+)/i'],
                'phone'=>['required'],
                'picture'=>['nullable'],
                'grade_id'=>['required'],
                'section_id'=>['required']
            ];



    }

    public function messages()
    {
        return [
            'required'=> ':attribute must be provided',
            'firstname.max'=> 'Name must not  be more than 255 charachters',
            'email.regex'=>'Please enter a valid email address',
            'unique'=>':attribute already exist',
        ];
    }


    public function attributes()
    {
        return [
            'grade_id'=> 'Class',
            'section_id'=> 'Section',

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

            $errors = collect( $validator->errors());
            $errors = $errors->collapse();

            $response = response()->json([
                "status"=>400,
                'success' => false,
                'message' => 'Ops! Some errors occurred',
                'errors' => $errors
            ]);


        throw (new ValidationException($validator, $response));
    }

}
