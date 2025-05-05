<?php

namespace App\Services\Traits;
use App\Enums\RuleContext;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
trait HasValidationRules
{
     /**
     * Get the validation rules based on the context.
     *
     * @param RuleContext $context The validation context
     * @param array $data Additional data to consider
     * @return array The validation rules
     */
    public function rules(RuleContext $context, array $data = [])
    {
        return match ($context) {
            RuleContext::UPDATE => $this->getUpdateRules($data),
            RuleContext::CREATE => $this->getCreateRules($data),
            RuleContext::PASSWORD_CHANGE => $this->getPasswordChangeRules($data),
            RuleContext::ADMIN_UPDATE => $this->getAdminRules($data),
            default => [],
        };
    }

    /**
     * Get rules for creating a new user.
     */
    protected function getCreateRules(array $data = []): array
    {
        return [
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email_pro' => 'required|string|email|max:255|unique:users,email_pro',
            'immatricul' => 'required|string|max:50|unique:users,immatricul',
            'password' => 'required|string|min:8',
          
        ];
    }

    /**
     * Get rules for updating a user.
     */
    protected function getUpdateRules(array $data = []): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email_pro' => [
                'sometimes', 
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email_pro')->ignore($this->id ?? $data['id'] ?? null),
            ],
            'immatricul' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('users', 'immatricul')->ignore($this->id ?? $data['id'] ?? null),
            ],
        ];
    }

    /**
     * Get rules for changing password.
     */
    protected function getPasswordChangeRules(array $data = []): array
    {
        return [
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ];
    }

    /**
     * Get rules for admin operations.
     */
    protected function getAdminRules(array $data = []): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email_pro' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email_pro')->ignore($this->id ?? $data['id'] ?? null),
            ],
            'immatricul' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'immatricul')->ignore($this->id ?? $data['id'] ?? null),
            ],
            'is_active' => 'sometimes|boolean',
            // Add any admin-specific rules here
        ];
    }

    /**
     * Validate data with the rules for the given context.
     *
     * @param RuleContext $context The validation context
     * @param array $data The data to validate
     * @return array The validated data
     * @throws ValidationException if validation fails
     */
    public function validate(RuleContext $context, array $data = [])
    {
        $rules = $this->rules($context, $data);
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        
        return $validator->validated();
    }
}
