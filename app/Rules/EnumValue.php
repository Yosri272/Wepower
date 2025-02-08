<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnumValue implements ValidationRule
{
    protected $enumClass;

    public function __construct($enumClass)
    {
        $this->enumClass = $enumClass;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! in_array($value, array_column($this->enumClass::cases(), 'value'))) {
            $fail('The selected :attribute is invalid.');
        }
    }
}
