<?php

class MysqliJw
{

    public function spojeni()
    {

        var_dump($_SERVER['SERVER_NAME']);
        if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
            // Localhost
            $mysqli = new mysqli('database', 'root', 'root', 'iordinace');
        } elseif ($_SERVER['SERVER_ADDR'] == '95.168.206.203') {
            // Ebola
            $mysqli = new mysqli('mysql3.ebola.cz', 'iordinacecz_user', '8WK4tXwGP0Cl', 'jwcz_mvcadmin2255');
        } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.jw.cz') {
            // iordinace.jw.cz
            $mysqli = new mysqli('127.0.0.1', '8uecqqoi', 'Tt4Xh\vm~r', 'iOrdinace');
            $mysqli->set_charset('utf8');
        } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.cz') {
            // iordinace.jw.cz
//            $mysqli = new mysqli('127.0.0.1', 'vbo3b73x', 'bey*XFC5ewk3yhe8zck', 'iOrdinaceProdu', 3311);
            $mysqli = new mysqli('database', 'root', 'root', 'iordinace');
            $mysqli->set_charset('utf8');
        }

        $mysqli->set_charset('utf8');

        if ($mysqli->connect_error) {
            die('Nepodařilo se připojit k MySQL serveru - tady (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
        }

        echo 'Připojení proběhlo úspěšně ' . $mysqli->host_info . "\n";

    }
}

