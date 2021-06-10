<?php

class NadstandardniSluzbyKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => 'nadstandardní služby, měření CRP, konzultace po telefonu.',
         'klicova_slova' => '',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
		  $this->data['admin'] = 'Administrator';

      $this->pohled = 'nadstandardni-sluzby';
   }
}
