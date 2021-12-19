<?php

namespace App\Rules;

use App\Models\Grade;
use Illuminate\Contracts\Validation\Rule;

class SectionRule implements Rule
{

    protected  $grade;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($gradeId)
    {
        //
        $this->grade = Grade::with("section")->where("id",$gradeId)->first();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $isTrue= true;
        if($this->grade){
            foreach ($this->grade->section as $res){
                if( $res->name == $value){
                    $isTrue = false;
                }
            }
        }
        return $isTrue;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Name already exists';
    }
}
