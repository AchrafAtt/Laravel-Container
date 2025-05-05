<?php

namespace App\Services\Traits;
use App\Enums\RuleContext;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
trait HasValidationRules
{
      /**
     * Get validation rules based on context
     *
     * @param RuleContext $context
     * @return array
     */
    public static function getRules(RuleContext $context = RuleContext::CREATE): array
    {
        // Method must be implemented by the model
        return static::defineRules($context);
    }
}
