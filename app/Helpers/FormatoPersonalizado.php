<?php

namespace App\Helpers;

class FormatoPersonalizado
{
    public static function getValue($numero)
    {
        return intval($numero) == floatval($numero) ? intval($numero) : $numero;
    }

    public static function getValueDecimal($numero, $decimals = 2)
    {
        return intval($numero) == floatval($numero) ? intval($numero) : number_format($numero, $decimals, '.', '');
    }

    public static function getExtencionFile($filename)
    {
        $array = explode('.', $filename);
        return $array[count($array) - 1];
    }

    public static function extraerMensaje($data)
    {
        $mensaje = '';
        if (count($data)) {
            foreach ($data as $key => $value) {
                if ($value > 0) {
                    $table = str_replace('_', ' ', $key);
                    $mensaje .= $mensaje == '' ? $table : ", $table";
                }
            }
        }
        return $mensaje == '' ? '' : "($mensaje)";
    }


}
