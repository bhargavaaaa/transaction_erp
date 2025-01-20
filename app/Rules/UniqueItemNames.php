<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueItemNames implements ValidationRule
{
    protected $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value)) {
            $itemNames = array_filter(array_column($this->array, 'barcode'));
            $duplicates = array_diff_assoc($itemNames, array_unique($itemNames));

            if(count($duplicates) !== 0) {
                $fail('The work order number must be unique within the file.');
            }
        }
    }
}
