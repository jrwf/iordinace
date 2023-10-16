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
            $nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
            $perex = (isset($_POST['perex'])) ? $_POST['perex'] : '';
            $obsah = (isset($_POST['obsah'])) ? $_POST['obsah'] : '';
            $zobrazit = (isset($_POST['zobrazit'])) ? $_POST['zobrazit'] : '';
            $mysql->query("insert into aktualita (nadpis, perex, obsah, zobrazit) values ('$nadpis','$perex','$obsah','$zobrazit')");
        }
    }

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
     * @return mysqli|null
     */
    public function getSpojeni()
    {
        $db = new \DatabaseConnection();
        $mysql = $db->spojeni();
        return $mysql;
    }
}
