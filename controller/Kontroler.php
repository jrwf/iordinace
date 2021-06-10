<?php

   // Výchozí kontroler pro DevbookMVC
   abstract class Kontroler
   {

      // Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
      protected $data = array();
      // Název šablony bez přípony
      protected $pohled = "";
      // Hlavička HTML stránky
      protected $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');

      // Ošetří proměnnou pro výpis do HTML stránky
      private function osetri($x = null)
      {
         if (!isset($x))
         return null;
         elseif (is_string($x))
         return htmlspecialchars($x, ENT_QUOTES);
         elseif (is_array($x))
         {
            foreach($x as $k => $v)
            {
               $x[$k] = $this->osetri($v);
            }
            return $x;
         }
         else 
         return $x;
      }

      // Vyrenderuje pohled
      public function vypisPohled()
      {
         if ($this->pohled)
         {
            extract($this->osetri($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require("view/" . $this->pohled . ".phtml");
         }
      }

      // Přidá zprávu pro uživatele
      public function pridejZpravu($zprava)
      {
         if (isset($_SESSION['zpravy']))
         $_SESSION['zpravy'][] = $zprava;
         else
         $_SESSION['zpravy'] = array($zprava);
      }

      // Vrátí zprávy pro uživatele
      public static function vratZpravy()
      {
         if (isset($_SESSION['zpravy']))
         {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']);
            return $zpravy;
         }
         else
         return array();
      }

      // Přesměruje na dané URL
      public function presmeruj($url)
      {
         header("Location: /$url");
         header("Connection: close");
         exit;
      }

      // Ověří, zda je přihlášený uživatel, případně přesměruje na login
      public function overUzivatele($admin = false)
      {
         $spravceUzivatelu = new SpravceUzivatelu();
         $uzivatel = $spravceUzivatelu->vratUzivatele();
         if (!$uzivatel || ($admin && !$uzivatel['admin']))
         {
            $this->pridejZpravu('Nedostatečná oprávnění.');
            $this->presmeruj('prihlaseni');
         }
      }

      // Hlavní metoda controlleru
      abstract function zpracuj($parametry);

   }
