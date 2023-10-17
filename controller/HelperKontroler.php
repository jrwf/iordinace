<?php

class HelperKontroler
{
    public static function zkratitText(string $retezec, int $maxDelka = 350): string
    {
        if (strlen($retezec) > $maxDelka) {
            $zkraceno = substr($retezec, 0, $maxDelka);
            $posledniMezera = strrpos($zkraceno, ' '); // najde poslední mezeru
            if ($posledniMezera !== false) {
                $retezec = substr($zkraceno, 0, $posledniMezera) . " ...";
            }
        }
        return $retezec;
    }
}