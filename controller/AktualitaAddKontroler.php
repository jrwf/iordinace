<?php

class AktualitaAddKontroler extends Kontroler
{

    /**
     * @inheritDoc
     */
    public function zpracuj($parametry)
    {
        $this->hlavicka = array(
            'titulek' => 'administrace',
            'klicova_slova' => '',
            'popis' => ''
        );

        $aktualita = new aktuality();
        $aktualita->vlozitAktualitu();

        $this->pohled = 'aktualita-add';
    }
}