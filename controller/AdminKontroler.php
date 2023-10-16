<?php

class AdminKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
        $this->hlavicka = array(
            'titulek' => 'administrace',
            'klicova_slova' => '',
            'popis' => ''
        );

        $this->data['administrace'] = 'administrace je tady';

        $this->pohled = 'admin';
    }
}