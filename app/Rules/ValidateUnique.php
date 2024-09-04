<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $table, $column, $value, $softdeletes, $except, $column2, $value2;

    public function __construct($table, $column, $value, $except = null, $softdeletes = true,  $column2 = null, $value2 = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->value = $value;
        $this->except = $except;
        $this->softdeletes = $softdeletes;
        $this->column2 = $column2;
        $this->value2 = $value2;
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
        $query =  DB::table($this->table)->where($this->column, mb_strtoupper(trim($this->value), 'UTF-8'));

        if ($this->column2) {
            $query->where($this->column2, mb_strtoupper(trim($this->value2), 'UTF-8'));
        }

        if ($this->softdeletes) {
            $query->whereNull('deleted_at');
        }

        if ($this->except) {
            $query->where('id', '<>',  $this->except);
        }

        return $query->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists.';
    }
}
