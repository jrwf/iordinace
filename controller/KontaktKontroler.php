<?php

class KontaktKontroler extends Kontroler
{
    /**
     * @param $parametry
     *
     * @return mixed|void
     */
    public function zpracuj($parametry)
    {
        $this->hlavicka = array(
            'titulek' => 'kontakt',
            'klicova_slova' => '',
            'popis' => ''
        );

        $zprava = '';

        if ($_POST) {
            if (isset($_POST["email"]) && !empty($_POST['email'])) {
                if (isset($_POST['zprava']) && !empty($_POST['zprava'])) {
                    if (isset($_POST['rok']) && ($_POST['rok'] === date("Y"))) {
                        $odesilacEmailu = new OdesilacEmailu();
                        $odesilacEmailu->odesli("jiri.wolf@jw.cz", "Email z webu", $_POST['zprava']);
//                        $odesilacEmailu->odesli("mprecechtelova@seznam.cz", "E-mail z webových stránek.", $_POST['zprava'], $_POST['email']);
//                        $odesilacEmailu->odesli("precechtelova@iordinace.cz", "E-mail z webových stránek.", $_POST['zprava'], $_POST['email']);
                        $zprava = 'Vaše zpráva byla odeslána.';
//                        header('Location: http://iordinace.loc/kontakt');
                        header('Location: https://www.iordinace.cz/kontakt');
                    } else {
                        $zprava = 'Musite zadat aktuální rok.';
                    }
                } else {
                    $zprava = 'Musíte zadat zprávu';
                }
            } else {
                $zprava = 'Musite zadat Váš email.';
            }
        }
        $this->data['hlaska'] = $zprava;
        $this->pohled = 'kontakt';
    }
}
