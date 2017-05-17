<?php

class ChangeString {

    private $abecedario = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'ñ', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    private $replace = array('b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'ñ', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'a');

    private function stro_replace($search, $replace, $subject) {
        return strtr($subject, array_combine($search, $replace));
    }

    public function build($String) {
        $String = $this->stro_replace($this->abecedario, $this->replace, $String);
        return $this->stro_replace(array_map('strtoupper', $this->abecedario), array_map('strtoupper', $this->replace), $String);
    }

}

$ChangeString = new ChangeString();
echo $ChangeString->build('123 abcd*3');
