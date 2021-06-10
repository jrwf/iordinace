<?php

class HomeKontroler extends Kontroler
{
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
      $this->data['aktualita'] = $addaktualita->vypsatAktualitu();
		  $this->data['zobrazit'] = $addaktualita->zobrazitAktualitu();

      $this->pohled = 'home';
   }
}
