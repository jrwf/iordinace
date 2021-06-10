<?php

class OdkazyKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => 'užitečné odkazy.',
         'klicova_slova' => '',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
		  $this->data['admin'] = 'Administrator';

      $this->pohled = 'odkazy';
   }
}
