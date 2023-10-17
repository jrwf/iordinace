<?php

class AktualitaKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
        $aktualita = new \Addaktualita();

        $this->hlavicka = array(
            'titulek' => '',
            'klicova_slova' => '',
            'popis' => ''
        );

        $aktualita->vlozitAktualitu();

        if ($_SESSION['logged_user'] != 'yes') {
            header('Location: login');
            exit;
        }

        $this->data['aktualita'] = $aktualita->vypsatAktualitu();
        $this->data['pokus'] = 'nejaky text';

        $this->pohled = 'aktualita';
    }
}
