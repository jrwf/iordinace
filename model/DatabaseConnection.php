<?php

class DatabaseConnection
{
    private $host = 'database';
    private $database = 'iordinace';
    private $username = 'root';
    private $password = 'root';

    // TODO - nastavit připojení k databázi pro různe servery

    /**
     * Připojení k databázi
     */
    public function spojeni()
    {
        try {
            $db = new mysqli($this->host, $this->username, $this->password, $this->database);

            // Kontrola, zda se povedlo připojení
            if ($db->connect_error) {
                throw new Exception('Nepodařilo se připojit k MySQL serveru - ' . $db->connect_error);
            }

            // Nastavení kódování
            $db->set_charset('utf8');

            // Kontrola, zda je nastaveno kódování správně
            if ($db->error) {
                throw new Exception('Chyba při nastavování kódování - ' . $db->error);
            }
//            echo 'Připojení proběhlo úspěšně ' . $db->host_info . "\n";
            return $db;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

