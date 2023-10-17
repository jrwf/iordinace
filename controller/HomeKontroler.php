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
      $zkracovac = HelperKontroler::zkratitText('Nějaký text', 10);

      $this->data['jmeno'] = 'Nejake jmeno';
      $this->data['admin'] = 'Administrator';
      $this->data['aktuality'] = $addaktualita->seznamAktualit();

      $this->pohled = 'home';
   }
}
