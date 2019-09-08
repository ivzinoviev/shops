<?php

namespace App\Rules;

use App\Session;
use Illuminate\Contracts\Validation\Rule;

class ProductAllowed implements Rule
{
    protected $productId;

    /**
     * Create a new rule instance.
     *
     * @param $productId
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
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
        return Session::getCurrent()->getRuntime()->isShopAllowProduct($value, $this->productId);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Товар не подходит данному магазину';
    }
}
