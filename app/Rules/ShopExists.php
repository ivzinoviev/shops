<?php

namespace App\Rules;

use App\Session;
use Illuminate\Contracts\Validation\Rule;

class ShopExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return Session::getCurrent()->getRuntime()->isShopExists($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Магазин не существует.';
    }
}
