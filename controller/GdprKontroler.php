<?php

class GdprKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => 'Informace pro pacienty o zpracování osobních údajů',
         'klicova_slova' => 'gdpr',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
      $this->data['admin'] = 'Administrator';

      $this->pohled = 'gdpr';
   }
}
