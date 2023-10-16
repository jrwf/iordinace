<?php

class AddaktualitaKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
        $addaktualita = new \Addaktualita();

        $this->hlavicka = array(
            'titulek' => '',
            'klicova_slova' => '',
            'popis' => ''
        );

        $addaktualita->vlozitAktualitu();

        if ($_SESSION['logged_user'] != 'yes') {
            header('Location: login');
            exit;
        }

        $this->data['aktualita'] = $addaktualita->vypsatAktualitu();
        $this->data['pokus'] = 'nejaky text';

        $this->pohled = 'addaktualita';
    }
}
