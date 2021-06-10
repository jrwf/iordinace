<?php

class TemplateKontroler extends Kontroler
{
   public function zpracuj($parametry)
   {
      $this->hlavicka = array(
         'titulek' => '',
         'klicova_slova' => '',
         'popis' => ''
      );

      $this->data['jmeno'] = 'Nejake jmeno';
		  $this->data['admin'] = 'Administrator';

      $this->pohled = 'template';
   }
}
