<?php

class DatabaseConnection
{
    protected string $host;
    protected string $database;
    protected string $username;
    protected string $password;

    public function __construct($host = 'database', $database = 'iordinace', $username = 'root', $password = 'root')
    {
        $this->loadEnvironmentVariables(dirname(__DIR__) . '/.env');
        $environment = getenv('ENVIRONMENT');
        $config = include dirname(__DIR__) . '/config.php';
        $envConfig = $config[$environment];

        $this->host = $envConfig['DB_HOST'];
        $this->database = $envConfig['DB_DATABASE'];
        $this->username = $envConfig['DB_USERNAME'];
        $this->password = $envConfig['DB_PASSWORD'];
    }

    /**
     * @param $filePath
     * @return void
     */
    public function loadEnvironmentVariables($filePath): void
    {
        if (!file_exists($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            // Ignorujte komentáře
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Odstraňte uvozovky, pokud existují
            $value = preg_replace('/^"(.*)"$/', '$1', $value);

            putenv("$name=$value");
        }
    }

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

