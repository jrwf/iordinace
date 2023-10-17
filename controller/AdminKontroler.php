<?php

class AdminKontroler extends Kontroler
{
    /**
     * @throws Exception
     */
    public function zpracuj($parametry)
    {
        $this->hlavicka = array(
            'titulek' => 'administrace',
            'klicova_slova' => '',
            'popis' => ''
        );

        $addaktuality = new \Addaktualita();

        $this->data['administrace'] = 'administrace je tady';
        $this->data['seznamAktualit'] = $addaktuality->seznamAktualit();

        $this->pohled = 'admin';
    }
}