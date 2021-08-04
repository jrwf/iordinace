<?php

class MysqliJw {

  public function spojeni() {

    if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
      $mysqli = new mysqli('localhost', 'root', '9#wB$7ppGgjC4g', 'iordinacec');
    }
    // Ebola
    elseif ($_SERVER['SERVER_ADDR'] == '95.168.206.203') {
      $mysqli = new mysqli('mysql3.ebola.cz', 'iordinacecz_user', '8WK4tXwGP0Cl', 'jwcz_mvcadmin2255');
    }

    $mysqli->set_charset('utf8');

    if ($mysqli->connect_error) {
      die('Nepodařilo se připojit k MySQL serveru (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
    }

    echo 'Připojení proběhlo úspěšně ' . $mysqli->host_info . "\n";

  }
}

