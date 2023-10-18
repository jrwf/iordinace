<?php

class AktualitaDeleteKontroler extends Kontroler
{

    /**
     * @inheritDoc
     */
    public function zpracuj($parametry)
    {
        $id = (int)$_GET['idaktualita'];

        $aktualita = new \aktuality();
        $aktualita->smazatAktualitu($id);
        header('Location: admin');
    }
}