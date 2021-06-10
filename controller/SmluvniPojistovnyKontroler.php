<?php

class SmluvniPojistovnyKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => 'seznam smluvních pojišťoven.',
         'klicova_slova' => '',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
		  $this->data['admin'] = 'Administrator';

      $this->pohled = 'smluvni-pojistovny';
   }
}
