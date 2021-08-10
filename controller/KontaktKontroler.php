<?php

class KontaktKontroler extends Kontroler
{
  public function zpracuj($parametry)
  {
    $this->hlavicka = array(
      'titulek' => 'kontakt',
      'klicova_slova' => '',
      'popis' => ''
    );

    //$rok = (isset($_POST['rok'])) ? $_POST['rok'] : '';
    $zprava = '';

    if($_POST) {
      if (isset($_POST["email"]) != empty($_POST['email'])) {
        if(isset($_POST['zprava']) != empty($_POST['zprava'])) { 
          if (isset($_POST['rok']) && ($_POST['rok'] == date("Y"))) {
            $odesilacEmailu = new OdesilacEmailu();
            $odesilacEmailu->odesli("jiri.wolf@jw.cz", "Email z webu", $_POST['zprava'], $_POST['email']);
            $odesilacEmailu->odesli("mprecechtelova@seznam.cz", "E-mail z webových stránek.", $_POST['zprava'], $_POST['email']);
            $odesilacEmailu->odesli("precechtelova@iordinace.cz", "E-mail z webových stránek.", $_POST['zprava'], $_POST['email']);
            $zprava = 'Vaše zpráva byla odeslána.';
          }
          else {
            $zprava = 'Musite zadat aktuální rok.';
          }
        }
        else {
          $zprava = 'Musíte zadat zprávu';
        }
      }
      else {
        $zprava = 'Musite zadat Váš email.';
      }
    }

    $this->data['hlaska'] = $zprava;

    $this->pohled = 'kontakt';
  }
}
