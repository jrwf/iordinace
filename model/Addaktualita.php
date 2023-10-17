<?php

class Addaktualita
{
    /**
     * Vloží aktualitu do databáze
     *
     * @return void
     */
    public function vlozitAktualitu()
    {
        $mysql = $this->getSpojeni();
        if ((isset($_POST['ok']))) {
            // TODO - ošetřit vstupní data
            $nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
            $perex = (isset($_POST['perex'])) ? $_POST['perex'] : '';
            $obsah = (isset($_POST['obsah'])) ? $_POST['obsah'] : '';
            $zobrazit = (isset($_POST['zobrazit'])) ? $_POST['zobrazit'] : '';
            $mysql->query("insert into aktualita (nadpis, perex, obsah, zobrazit) values ('$nadpis','$perex','$obsah','$zobrazit')");
            header('Location: admin');
            exit();
        }
    }

    /**
     * @return bool|mysqli_result
     */
    public function zobrazitAktualitu()
    {
        $mysql = $this->getSpojeni();
        $data = $mysql->query("select zobrazit from aktualita where idaktualita = 1");
        return $data;
    }

    public function vypsatAktualitu()
    {
        $mysql = $this->getSpojeni();
        $mysql->set_charset('utf8');
        $data = $mysql->query("select * from aktualita where idaktualita = 1");
        return $data;
    }

    /**
     * Vrací seznam aktualit.
     *
     * @return array|false|null
     * @throws Exception
     */
    public function seznamAktualit()
    {
        $mysql = $this->getSpojeni();
        try {
            return $mysql->query("select idaktualita, nadpis, perex, obsah, DATE_FORMAT(ts, '%e. %m. %Y') as datum from aktualita order by idaktualita desc")->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Chyba při získávání seznamu aktualit - ' . $e->getMessage());
        }
    }

    public function detailAktuality(int $id)
    {
        $mysql = $this->getSpojeni();
        return $mysql->query("select * from aktualita where idaktualita = $id")->fetch_assoc();
    }

    public function smazatAktualitu(int $id)
    {
        $mysql = $this->getSpojeni();
        $mysql->query("delete from aktualita where idaktualita = $id");
    }

    /**
     * @return mysqli|null
     */
    public function getSpojeni()
    {
        $db = new \DatabaseConnection();
        $mysql = $db->spojeni();
        return $mysql;
    }
}
