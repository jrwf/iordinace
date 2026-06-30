<?php

class aktuality
{
    /**
     * Vloží aktualitu do databáze
     *
     * @return void
     * @throws Exception
     */
    public function vlozitAktualitu(): void
    {
        if (!isset($_POST['ok'])) {
            return;
        }

        $nadpis  = $_POST['nadpis']  ?? '';
        $perex   = $_POST['perex']   ?? '';
        $obsah   = $_POST['obsah']   ?? '';
        $zobrazit = $_POST['zobrazit'] ?? '';

        $mysql = $this->getSpojeni();
        $mysql->query("UPDATE aktualita SET orders = orders + 1");

        $stmt = $mysql->prepare("INSERT INTO aktualita (orders, nadpis, perex, obsah, zobrazit) VALUES (1, ?, ?, ?, ?)");
        $stmt->bind_param('ssss', $nadpis, $perex, $obsah, $zobrazit);
        $stmt->execute();
        $stmt->close();

        header('Location: admin');
        exit();
    }

    /**
     * @param int $id
     * @return void
     */
    public function updateAktualitu(int $id): void
    {
        if (!isset($_POST['ok'])) {
            return;
        }

        $nadpis   = $_POST['nadpis']   ?? '';
        $perex    = $_POST['perex']    ?? '';
        $obsah    = $_POST['obsah']    ?? '';
        $zobrazit = $_POST['zobrazit'] ?? '';

        $mysql = $this->getSpojeni();
        $stmt = $mysql->prepare("UPDATE aktualita SET nadpis = ?, perex = ?, obsah = ?, zobrazit = ? WHERE idaktualita = ?");
        $stmt->bind_param('ssssi', $nadpis, $perex, $obsah, $zobrazit, $id);
        $stmt->execute();
        $stmt->close();

        header('Location: admin');
        exit();
    }

    /**
     * Upraví pořadí aktuality.
     *
     * @param array $data
     * @return void
     */
    public function updateAktualitaOrder(array $data): void
    {
        $mysql = $this->getSpojeni();
        $ids = $data['order'];
        $orders = array_combine(range(1, count($ids)), $ids);

        $stmt = $mysql->prepare("UPDATE aktualita SET orders = ? WHERE idaktualita = ?");
        foreach ($orders as $poradi => $id) {
            $poradi = (int) $poradi;
            $id     = (int) $id;
            $stmt->bind_param('ii', $poradi, $id);
            $stmt->execute();
        }
        $stmt->close();
    }

    /**
     * @param int $idaktualita
     * @return array|false|null
     */
    public function vypsatAktualitu(int $idaktualita): false|array|null
    {
        $mysql = $this->getSpojeni();
        $mysql->set_charset('utf8');

        $stmt = $mysql->prepare("SELECT idaktualita, DATE_FORMAT(ts, '%d. %m. %Y') AS datum, nadpis, perex, obsah FROM aktualita WHERE idaktualita = ?");
        $stmt->bind_param('i', $idaktualita);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
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
            $sql = "SELECT orders, idaktualita, nadpis, perex, obsah, DATE_FORMAT(created, '%e. %m. %Y') AS datum FROM aktualita ORDER BY orders ASC";
            if ($limit !== null) {
                $stmt = $mysql->prepare($sql . " LIMIT ?");
                $stmt->bind_param('i', $limit);
            } else {
                $stmt = $mysql->prepare($sql);
            }
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
        $stmt = $mysql->prepare("SELECT * FROM aktualita WHERE idaktualita = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * @param int $id
     * @return void
     */
    public function smazatAktualitu(int $id): void
    {
        $mysql = $this->getSpojeni();
        $stmt = $mysql->prepare("DELETE FROM aktualita WHERE idaktualita = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @return mysqli|null
     */
    public function getSpojeni(): ?mysqli
    {
        $db = new \DatabaseConnection();
        return $db->spojeni();
    }
}
