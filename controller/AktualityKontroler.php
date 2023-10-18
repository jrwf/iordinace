<?php

class AktualityKontroler extends Kontroler
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function zpracuj($parametry)
    {
        $this->hlavicka = array(
            'titulek' => 'administrace',
            'klicova_slova' => '',
            'popis' => ''
        );

        $aktuality = new aktuality();
        $this->data['aktuality'] = $aktuality->seznamAktualit();

        $this->pohled = 'aktuality';
    }
}