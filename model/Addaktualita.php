<?php

class Addaktualita
{
    public function vlozitAktualitu()
    {
        if ((isset($_POST['ok']))) {

            if ($_SERVER['SERVER_NAME'] == 'iordinace.loc') {
                $mysqli = new mysqli('localhost', 'root', '9#wB$7ppGgjC4g', 'iordinace');
                $mysqli->set_charset('utf8');
            } //Ebola
            elseif ($_SERVER['SERVER_ADDR'] == '95.168.206.196') {
                $mysqli = new mysqli('mysql2.ebola.cz', 'jwcz_u', 'admin2255', 'jwcz_iordinace');
                $mysqli->set_charset('utf8');
            } // Ebola
            elseif ($_SERVER['SERVER_ADDR'] == '95.168.206.203') {
                $mysqli = new mysqli('mysql3.ebola.cz', 'iordinacecz_user', '8WK4tXwGP0Cl', 'iordinacecz_sql');
            } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.cz' || $_SERVER['SERVER_NAME'] === 'www.iordinace.cz') {
                // iordinace.cz
                $mysqli = new mysqli('127.0.0.1', 'vbo3b73x', 'bey*XFC5ewk3yhe8zck', 'iOrdinaceProdu', 3311);
                $mysqli->set_charset('utf8');
            }

            //$mysqli = new mysqli('localhost', 'root', 'heslo', 'iordinace');
            //$mysqli->set_charset('utf8');

            if ($mysqli->connect_error) {
                die('Nepodařilo se připojit k MySQL serveru - aktualita (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
            }
            $nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
            $perex = (isset($_POST['perex'])) ? $_POST['perex'] : '';
            $obsah = (isset($_POST['obsah'])) ? $_POST['obsah'] : '';
            $zobrazit = (isset($_POST['zobrazit'])) ? $_POST['zobrazit'] : '';

            //$nadpis = 'letadlo';
            //$obsah = 'motorka';

            $mysqli->query("update aktualita set nadpis = '$nadpis', perex = '$perex' , obsah = '$obsah', zobrazit = '$zobrazit' where idaktualita = 1");
//            $mysqli->query("update aktualita set nadpis = '$nadpis', obsah = '$obsah', zobrazit = '$zobrazit' where idaktualita = 1");
        }
    }

    public function zobrazitAktualitu()
    {
        //$mysqli = new mysqli('localhost', 'root', 'heslo', 'iordinace');
        //$mysqli->set_charset('utf8');
        if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] === 'localhost') {
            $mysqli = new mysqli('database', 'root', 'root', 'iordinace-mysql');
            $mysqli->set_charset('utf8');
        } //Ebola
        elseif ($_SERVER['SERVER_NAME'] == '95.168.206.196') {
            $mysqli = new mysqli('mysql2.ebola.cz', 'jwcz_u', 'admin2255', 'jwcz_iordinace');
            $mysqli->set_charset('utf8');
        } // Ebola
        elseif ($_SERVER['SERVER_NAME'] == '95.168.206.203') {
            $mysqli = new mysqli('mysql3.ebola.cz', 'iordinacecz_user', '8WK4tXwGP0Cl', 'iordinacecz_sql');
        } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.jw.cz') {
            // iordinace.jw.cz
            $mysqli = new mysqli('127.0.0.1', '8uecqqoi', 'Tt4Xh\vm~r', 'iOrdinace', 3311);
            $mysqli->set_charset('utf8');
        } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.cz' || $_SERVER['SERVER_NAME'] === 'www.iordinace.cz') {
            // iordinace.jw.cz
            $mysqli = new mysqli('127.0.0.1', 'vbo3b73x', 'bey*XFC5ewk3yhe8zck', 'iOrdinaceProdu', 3311);
            $mysqli->set_charset('utf8');
        }

        if ($mysqli->connect_error) {
            die('Nepodařilo se připojit k MySQL serveru - aktualita2 (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
        }
        $data = $mysqli->query("select zobrazit from aktualita where idaktualita = 1");
        return $data;
    }

    public function vypsatAktualitu()
    {
        //$mysqli = new mysqli('localhost', 'root', 'heslo', 'iordinace');
        //$mysqli->set_charset('utf8');
        if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] === 'localhost') {
            $mysqli = new mysqli('database', 'root', 'root', 'iordinace-mysql');
            $mysqli->set_charset('utf8');
        } elseif ($_SERVER['SERVER_NAME'] == '95.168.206.196') {
            // Ebola
            $mysqli = new mysqli('mysql2.ebola.cz', 'jwcz_u', 'admin2255', 'jwcz_iordinace');
            $mysqli->set_charset('utf8');
        } elseif ($_SERVER['SERVER_NAME'] == '95.168.206.203') {
            // Ebola
            $mysqli = new mysqli('mysql3.ebola.cz', 'iordinacecz_user', '8WK4tXwGP0Cl', 'iordinacecz_sql');
        } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.jw.cz') {
            // iordinace.jw.cz
            $mysqli = new mysqli('127.0.0.1', '8uecqqoi', 'Tt4Xh\vm~r', 'iOrdinace', 3311);
            $mysqli->set_charset('utf8');
        } elseif ($_SERVER['SERVER_NAME'] === 'iordinace.cz' || $_SERVER['SERVER_NAME'] === 'www.iordinace.cz') {
            // iordinace.cz
            $mysqli = new mysqli('127.0.0.1', 'vbo3b73x', 'bey*XFC5ewk3yhe8zck', 'iOrdinaceProdu', 3311);
            $mysqli->set_charset('utf8');
        }

        if ($mysqli->connect_error) {
            die('Nepodařilo se připojit k MySQL serveru - aktualita 31 (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
        }

        $mysqli->set_charset('utf8');
        $data = $mysqli->query("select * from aktualita where idaktualita = 1");
        return $data;
    }
}

?>
