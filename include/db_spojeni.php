<?php
    if ($_SERVER['SERVER_NAME'] == 'iordinace.loc') {
      $mysqli = new mysqli('localhost', 'root', '9#wB$7ppGgjC4g', 'iordinace');
      $mysqli->set_charset('utf8');
    } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.jw.cz') {
        $mysqli = new mysqli('127.0.0.1', '8uecqqoi', 'Tt4Xh\vm~r', 'iOrdinace');
        $mysqli->set_charset('utf8');
    }
     //Ebola
//    elseif ($_SERVER['SERVER_ADDR'] == '95.168.206.196') {
//      $mysqli = new mysqli('mysql2.ebola.cz', 'jwcz_u', 'admin2255', 'jwcz_iordinace');
//      $mysqli->set_charset('utf8');
//    }
    // Ebola
//    elseif ($_SERVER['SERVER_ADDR'] == '95.168.206.203') {
//      $mysqli = new mysqli('mysql3.ebola.cz', 'iordinacecz_user', '8WK4tXwGP0Cl', 'iordinacecz_sql');
//    }

    if ($mysqli->connect_error) {
      die('Nepodařilo se připojit k MySQL serveru (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
    }
    echo 'Připojení proběhlo úspěšně ' . $mysqli->host_info . "\n";

?>
