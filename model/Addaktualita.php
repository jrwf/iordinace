<?php

class Addaktualita
{
    /**
     * Vloží aktualitu do databáze
     *
     * @return void
     */
    public function vlozitAktualitu(): void
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

    public function upravitAktualitu(int $id)
    {
        $mysql = $this->getSpojeni();
        if ((isset($_POST['ok']))) {
            // TODO - ošetřit vstupní data
            $nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
            $perex = (isset($_POST['perex'])) ? $_POST['perex'] : '';
            $obsah = (isset($_POST['obsah'])) ? $_POST['obsah'] : '';
            $zobrazit = (isset($_POST['zobrazit'])) ? $_POST['zobrazit'] : '';
//            $mysql->query("insert into aktualita (nadpis, perex, obsah, zobrazit) values ('$nadpis','$perex','$obsah','$zobrazit')");
            $mysql->query("update aktualita set nadpis = '$nadpis', perex = '$perex', obsah = '$obsah', zobrazit = '$zobrazit' where idaktualita = $id");
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

    /**
     * @param int $idaktualita
     * @return array|false|null
     */
    public function vypsatAktualitu(int $idaktualita)
    {
        $mysql = $this->getSpojeni();
        $mysql->set_charset('utf8');
        $data = $mysql->query("select 
                                        idaktualita,
                                        DATE_FORMAT(ts, '%d. %m. %Y') as datum, 
                                        nadpis, 
                                        perex, 
                                        obsah 
                                        from aktualita 
                                        where idaktualita = $idaktualita")->fetch_assoc();
        return $data;
    }

    /**
     * Vrací seznam aktualit.
     *
     * @param int|null $limit
     * @return array
     * @throws Exception
     */
    public function seznamAktualit(?int $limit = null): array
    {
        $mysql = $this->getSpojeni();
        try {
            $limitClause = $limit !== null ? "LIMIT $limit" : '';
            return $mysql->query("SELECT idaktualita, nadpis, perex, obsah, DATE_FORMAT(ts, '%e. %m. %Y') as datum FROM aktualita ORDER BY idaktualita DESC $limitClause")->fetch_all(MYSQLI_ASSOC);
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
