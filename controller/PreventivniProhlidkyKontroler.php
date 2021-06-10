<?php

class PreventivniProhlidkyKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => 'preventivni prohlídky',
         'klicova_slova' => '',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
		  $this->data['admin'] = 'Administrator';

      $this->pohled = 'preventivni-prohlidky';
   }
}
