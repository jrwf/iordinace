<?php

class LogoutKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => '',
         'klicova_slova' => '',
         'popis' => ''
      );

      //$this->data['jmeno'] = 'Nejake jmeno';

      $this->pohled = 'logout';
   }
}
