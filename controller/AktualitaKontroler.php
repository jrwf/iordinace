<?php

class AktualitaKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
        $idaktualita = (int)$_GET['idaktualita'];

        $aktualita = new \aktuality();

        $this->hlavicka = array(
            'titulek' => '',
            'klicova_slova' => '',
            'popis' => ''
        );

        $aktualita->vlozitAktualitu();

/*        if ($_SESSION['logged_user'] != 'yes') {
            header('Location: login');
            exit;
        }*/

        $this->data['aktualita'] = $aktualita->vypsatAktualitu($idaktualita);

        $this->pohled = 'aktualita';
    }
}
