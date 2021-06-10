<?php

class LoginKontroler extends Kontroler
{

  public $sesna;

  public function zpracuj($parametry)
  {

    $this->hlavicka = array(
      'titulek' => '',
      'klicova_slova' => '',
      'popis' => ''
    );

    $hlaska = '';

    $logovani = new Login();
    //$logovani->logovani();
    $logovani->presmerovani();

    $this->data['hlaska'] = $logovani->logovani();
    $this->data['sesna'] = $this->sesna;

    $this->pohled = 'login';
  }
}
