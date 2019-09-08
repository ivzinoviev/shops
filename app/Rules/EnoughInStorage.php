<?php

namespace App\Rules;

use App\Session;
use Illuminate\Contracts\Validation\Rule;

class EnoughInStorage implements Rule
{
    protected $requiredValue;

    protected $session;

    /**
     * Create a new rule instance.
     *
     * @param $requiredValue
     */
    public function __construct($requiredValue)
    {
        $this->requiredValue = $requiredValue;
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
        return Session::getCurrent()->getRuntime()->getStorageStockCount($value) >= $this->requiredValue;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Товара недостаточно на складе.';
    }
}
