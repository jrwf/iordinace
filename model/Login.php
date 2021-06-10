<?php

class Login
{
  public $hlaska;
  public $sesna;

  public function presmerovani() {
    if($_SERVER['REQUEST_URI'] == '/login') {
      if(isset($_SESSION['logged_user']) && ($_SESSION['logged_user'] === 'yes')) {
        header('Location: addaktualita');
        exit;
      }
    }
  }

  public function logovani()
  {
    if($_POST) {
      if(isset($_POST['username']) && ( $_POST['username'] == 'mprecechtelova@seznam.cz' )) {
        if( isset($_POST['heslo']) && ( $_POST['heslo'] == 'shotokan' ) ) {
          $_SESSION['logged_user'] = 'yes';
          header('Location: addaktualita');
          exit;
          //return $this->hlaska;
        }
        else {
          $this->hlaska =  'Asi preklep, zkuste to jeste jednou.';
          return $this->hlaska;
        }
      } 
      else {
        $this->hlaska = 'Zadejte spravne uzivatelske jmeno.';
        return $this->hlaska;
      }
    }
  }

}

?>
