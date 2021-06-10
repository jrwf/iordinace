<?php

class ProBudouciMaminkyKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
        'titulek' => 'informace pro budoucí maminky.',
         'klicova_slova' => '',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
		  $this->data['admin'] = 'Administrator';

      $this->pohled = 'pro-budouci-maminky';
   }
}
