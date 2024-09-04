<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ValidateDocument implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $code, $message;

    public function __construct($code)
    {
        $this->code = $code;
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
        if ($this->code == "01") {
            if (Str::length(trim($value)) == 11) {
                return true;
            } else {
                $this->message = "El campo :attribute requiere 11 dígitod para generar comprobante.";
                return false;
            }
        } else {

            if (Str::length(trim($value)) == 8 || Str::length(trim($value)) == 11) {
                return true;
            } else {
                $this->message = "Cantidad de dígitos del campo :attribute es inválido.";
                return false;
            }

            // $this->message = "The :attribute requiere 11 carácteres para generar comprobante.";
            // return false;
        }
        // return ($this->code == "01" && Str::length(trim($value)) == 11) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
