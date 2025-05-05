<?php

namespace Database\Factories;
use App\Models\User; 

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contractTypes = ['FullTime', 'PartTime', 'Contractor'];
        $familySituations = ['single', 'married', 'divorced'];
        $hireDate = $this->faker->dateTimeBetween('-5 years', 'now');
        
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email_pro' => $this->faker->unique()->companyEmail(),
            'email_verified_at' => $this->faker->randomElement([now(), null]),
            'immatricul' => 'EMP' . $this->faker->unique()->numerify('######'),
            'personal_email' => $this->faker->randomElement([$this->faker->safeEmail(), null]),
            'password' => Hash::make('password'), // Default password for testing
            'profile_picture' => $this->faker->randomElement([
                $this->faker->imageUrl(150, 150, 'people'),
                null
            ]),
            'family_situation' => $this->faker->randomElement(
                array_merge($familySituations, [null])
            ),
            'position' => $this->faker->randomElement([
                $this->faker->jobTitle(),
                null
            ]),
            'address' => $this->faker->randomElement([
                $this->faker->address(),
                null
            ]),
            'country' => $this->faker->randomElement([
                $this->faker->country(),
                null
            ]),
            'id_number' => $this->faker->randomElement([
                'ID' . $this->faker->numerify('#########'),
                null
            ]),
            'ssn' => $this->faker->randomElement([
                $this->faker->numerify('###-##-####'),
                null
            ]),
            'bank_account' => $this->faker->randomElement([
                $this->faker->iban(),
                null
            ]),
            'birth_place' => $this->faker->randomElement([
                $this->faker->city(),
                null
            ]),
            'children_count' => $this->faker->randomElement([
                $this->faker->numberBetween(0, 5),
                null
            ]),
            'contract_type' => $this->faker->randomElement(
                array_merge($contractTypes, [null])
            ),
            'leave_balance' => $this->faker->randomElement([
                $this->faker->randomFloat(2, 0, 30),
                null
            ]),
            'hire_date' => $this->faker->randomElement([
                $hireDate,
                null
            ]),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'created_by' => null, // Will be handled in a seeder
            'updated_by' => null, // Will be handled in a seeder
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is active.
     *
     * @return $this
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Indicate that the user is inactive.
     *
     * @return $this
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    // /**
    //  * Indicate that the user is an admin.
    //  *
    //  * @return $this
    //  */
    // public function admin()
    // {
    //     return $this->state(function (array $attributes) {
    //         return [
    //             'position' => 'Administrator',
    //             'contract_type' => 'FullTime',
    //         ];
    //     });
    // }
    
    /**
     * Indicate that the user is married with children.
     *
     * @return $this
     */
    public function marriedWithChildren()
    {
        return $this->state(function (array $attributes) {
            return [
                'family_situation' => 'married',
                'children_count' => $this->faker->numberBetween(1, 4),
            ];
        });
    }
    
    /**
     * Set user's work experience based on contract type.
     *
     * @return $this
     */
    public function experienced()
    {
        return $this->state(function (array $attributes) {
            return [
                'hire_date' => $this->faker->dateTimeBetween('-10 years', '-2 years'),
                'leave_balance' => $this->faker->randomFloat(2, 10, 30),
            ];
        });
    }
   
}
