<?php

class HomeKontroler extends Kontroler
{
    /**
     * @throws Exception
     */
    public function zpracuj($parametry)
    {
        $this->hlavicka = array(
            'titulek' => 'ordinace dětské lékařky MUDr. Přecechtělové',
            'klicova_slova' => '',
            'popis' => ''
        );

        $addaktualita = new Addaktualita();

        $this->data['jmeno'] = 'Nejake jmeno';
        $this->data['admin'] = 'Administrator';
        $this->data['aktuality'] = $addaktualita->seznamAktualit(6);

        $this->pohled = 'home';
    }
}
