<?php

class AktualitaEditKontroler extends Kontroler
{
    /**
     * @inheritDoc
     */
    public function zpracuj($parametry)
    {
        $id = (int)$_GET['idaktualita'];

        $this->hlavicka = array(
            'titulek' => 'administrace',
            'klicova_slova' => '',
            'popis' => ''
        );

        $aktualita = new \Addaktualita();
        $this->data['aktualita'] = $aktualita->detailAktuality($id);
        $aktualita->updateAktualitu($id);

        $this->pohled = 'aktualita-edit';
    }
}