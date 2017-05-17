<?php

class ClearPar {

    public function build($cadena) {
        $array_cadena = str_split($cadena);
        foreach ($array_cadena as $k => $v) {
            if ($v !== '(' && $v !== ')') {
                unset($array_cadena[$k]);
            }
        }
        $new_cadena = '';
        foreach ($array_cadena as $k => $v) {
            if (isset($array_cadena[$k - 1])) {
                if ($array_cadena[$k - 1] === '(' && $array_cadena[$k] === ')') {
                    $new_cadena.=$array_cadena[$k];
                }
            }
            if (isset($array_cadena[$k + 1])) {
                if ($array_cadena[$k] === '(' && $array_cadena[$k + 1] === ')') {
                    $new_cadena.=$array_cadena[$k];
                }
            }
        }
        return $new_cadena;
    }

}

$ClearPar = new ClearPar();
echo $ClearPar->build('()())()');
