<?php

class Addaktualita
{
    /**
     * Vloží aktualitu do databáze
     *
     * @return void
     * @throws Exception
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

            $mysql->query("UPDATE aktualita SET orders = orders + 1");
            $mysql->query("insert into aktualita (orders, nadpis, perex, obsah, zobrazit) values (1, '$nadpis', '$perex', '$obsah', '$zobrazit')");
            header('Location: admin');
            exit();
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function updateAktualitu(int $id): void
    {
        $mysql = $this->getSpojeni();
        if ((isset($_POST['ok']))) {
            // TODO - ošetřit vstupní data
            $nadpis = (isset($_POST['nadpis'])) ? $_POST['nadpis'] : '';
            $perex = (isset($_POST['perex'])) ? $_POST['perex'] : '';
            $obsah = (isset($_POST['obsah'])) ? $_POST['obsah'] : '';
            $zobrazit = (isset($_POST['zobrazit'])) ? $_POST['zobrazit'] : '';
            $mysql->query("update aktualita set nadpis = '$nadpis', perex = '$perex', obsah = '$obsah', zobrazit = '$zobrazit' where idaktualita = $id");
            header('Location: admin');
            exit();
        }
    }

    /**
     * Upraví pořadí aktuality.
     *
     * @param array $data
     * @return void
     */
    public function updateAktualitaOrder(array $data): void
    {
        $mysql = $this->getSpojeni(); // TODO - přesunout do constructoru.
        $ids = $data['order'];
        $orders = array_combine(range(1, count($ids)), $ids);
        foreach ($orders as $id => $poradi) {
            $poradi = (int) $poradi;
            $mysql->query("update aktualita set orders = $id where idaktualita = $poradi");
        }
    }

    /**
     * @param int $idaktualita
     * @return array|false|null
     */
    public function vypsatAktualitu(int $idaktualita): false|array|null
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
            return $mysql->query("SELECT orders, idaktualita, nadpis, perex, obsah, DATE_FORMAT(created, '%e. %m. %Y') as datum FROM aktualita ORDER BY orders ASC $limitClause")->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Chyba při získávání seznamu aktualit - ' . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return false|array|null
     */
    public function detailAktuality(int $id): false|array|null
    {
        $mysql = $this->getSpojeni();
        return $mysql->query("select * from aktualita where idaktualita = $id")->fetch_assoc();
    }

    /**
     * @param int $id
     * @return void
     */
    public function smazatAktualitu(int $id): void
    {
        $mysql = $this->getSpojeni();
        $mysql->query("delete from aktualita where idaktualita = $id");
    }

    /**
     * @return mysqli|null
     */
    public function getSpojeni(): ?mysqli
    {
        $db = new \DatabaseConnection();
        $mysql = $db->spojeni();
        return $mysql;
    }
}
