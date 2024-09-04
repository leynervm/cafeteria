<?php

namespace App\Rules;

use App\Models\Mesa;
use Illuminate\Contracts\Validation\Rule;

class ValidateMesaDisonible implements Rule
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
        $status = Mesa::find($value)->status;
        return $status === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La mesa se encuentra ocupada.';
    }
}
