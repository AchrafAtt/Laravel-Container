<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RuleContext;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Services\Traits\HasValidationRules;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasValidationRules,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'work_email',
        'personal_email',
        'registration_number',
        'password',
        'profile_picture',
        'family_situation',
        'position',
        'address',
        'country',
        'id_number',
        'ssn',
        'bank_account',
        'birth_place',
        'children_count',
        'contract_type',
        'leave_balance',
        'hire_date',
        'is_active',
        'created_by',
        'updated_by',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
        'refresh_token',
        'ssn',
        'bank_account',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'children_count' => 'integer',
            'leave_balance' => 'float',
            'hire_date' => 'date',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


     /**
     * Define validation rules based on context
     *
     * @param RuleContext $context
     * @return array
     */
    public static function defineRules(RuleContext $context): array
    {
        // Base rules for all contexts
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_pro' => 'required|email|max:255',
            'personal_email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
            'family_situation' => 'nullable|in:single,married,divorced',
            'country' => 'nullable|string|max:100',
            'children_count' => 'nullable|integer|min:0',
            'contract_type' => 'nullable|in:FullTime,PartTime,Contractor',
            'leave_balance' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
        ];
        
        // Context-specific rules
        return match($context) {
            RuleContext::CREATE => array_merge($rules, [
                'email_pro' => 'required|email|max:255|unique:users',
                'immatricul' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]),
            
            RuleContext::UPDATE => array_merge($rules, [
                'email_pro' => 'required|email|max:255|unique:users,email_pro,{id}',
                'immatricul' => 'required|string|max:255|unique:users,immatricul,{id}',
                'password' => 'nullable|string|min:8',
                'is_active' => 'boolean',
            ]),
            
            RuleContext::PASSWORD_CHANGE => [
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
            ],
            
            default => $rules,
        };
    }
}
