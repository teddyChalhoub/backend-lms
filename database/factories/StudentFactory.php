<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Type\Integer;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname'=> $this->faker->firstName(),
            'lastname'=>$this->faker->lastName(),
            'email'=>$this->faker->unique()->safeEmail(),
            'picture'=>$this->faker->image(),
            'phone'=>$this->faker->phoneNumber(),
            'grade_id'=>$this->faker->numberBetween(1,51),
            'section_id'=>$this->faker->numberBetween(1,51),
            'student_id'=>$this->faker->numberBetween(1,51),
        ];
    }
}
