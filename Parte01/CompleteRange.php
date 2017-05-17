<?php

class CompleteRange {

    public function build($entrada) {
        $entrada = explode(',', $entrada);
        //Elimina los elementos que no sean numeros enteros
        foreach ($entrada as $k => $v) {
            if (!filter_var($v, FILTER_VALIDATE_INT)) {
                unset($entrada[$k]);
            }
        }
        $min = min($entrada);
        $max = max($entrada);
        $array_result = array();
        foreach (range($min, $max) as $número) {
            $array_result[] = $número;
        }
        return implode(',', $array_result);
    }

}

$CompleteRange = new CompleteRange();
echo $CompleteRange->build('55,58, 60');
