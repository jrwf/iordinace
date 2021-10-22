<?php

/**
 * Výchozí kontroler pro DevbookMVC
 */
abstract class Kontroler
{

    // Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
    protected $data = array();

    // Název šablony bez přípony
    protected $pohled = "";

    // Hlavička HTML stránky
    protected $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');


    /**
     * Ošetří proměnnou pro výpis do HTML stránky
     *
     * @param null $x
     */
    private function osetri($x = null)
    {
        if (!isset($x)) {
            return null;
        }

        if (is_string($x)) {
            return htmlspecialchars($x, ENT_QUOTES);
        }

        if (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->osetri($v);
            }
        }
        return $x;
    }


    /**
     * Vyrenderuje pohled
     */
    public function vypisPohled()
    {
        if ($this->pohled) {
            extract($this->osetri($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require("view/" . $this->pohled . ".phtml");
        }
    }


    /**
     * Přidá zprávu pro uživatele
     *
     * @param $zprava
     */
    public function pridejZpravu($zprava)
    {
        if (isset($_SESSION['zpravy'])) {
            $_SESSION['zpravy'][] = $zprava;
        } else {
            $_SESSION['zpravy'] = array($zprava);
        }
    }


    /**
     * Vrátí zprávy pro uživatele
     *
     * @return array|mixed
     */
    public static function vratZpravy()
    {
        if (isset($_SESSION['zpravy'])) {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']);
            return $zpravy;
        } else {
            return array();
        }
    }

    //

    /**
     * Přesměruje na dané URL
     *
     * @param $url
     */
    public function presmeruj($url)
    {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    //

    /**
     * Ověří, zda je přihlášený uživatel, případně přesměruje na login
     *
     * @param false $admin
     */
    public function overUzivatele($admin = false)
    {
        $spravceUzivatelu = new SpravceUzivatelu();
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        if (!$uzivatel || ($admin && !$uzivatel['admin'])) {
            $this->pridejZpravu('Nedostatečná oprávnění.');
            $this->presmeruj('prihlaseni');
        }
    }

    //

    /**
     * Hlavní metoda controlleru
     *
     * @param $parametry
     *
     * @return mixed
     */
    abstract public function zpracuj($parametry);

}
