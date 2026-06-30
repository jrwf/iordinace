<?php

class Login
{
    public $hlaska;
    public $sesna;

    /**
     * @return void
     */
    public function presmerovani()
    {
        if ($_SERVER['REQUEST_URI'] == '/login') {
            if (isset($_SESSION['logged_user']) && ($_SESSION['logged_user'] === 'yes')) {
                header('Location: admin');
                exit;
            }
        }
    }

    /**
     * @return string|void
     */
    public function logovani()
    {
        if ($_POST) {
            $this->nactiEnv(dirname(__DIR__) . '/.env');
            $adminUsername = getenv('ADMIN_USERNAME');
            $adminPasswordHash = getenv('ADMIN_PASSWORD_HASH');

            if (isset($_POST['username']) && $_POST['username'] === $adminUsername) {
                if (isset($_POST['heslo']) && password_verify($_POST['heslo'], $adminPasswordHash)) {
                    $_SESSION['logged_user'] = 'yes';
                    header('Location: admin');
                    exit;
                } else {
                    $this->hlaska = 'Asi preklep, zkuste to jeste jednou.';
                    return $this->hlaska;
                }
            } else {
                $this->hlaska = 'Zadejte spravne uzivatelske jmeno.';
                return $this->hlaska;
            }
        }
    }

    private function nactiEnv(string $filePath): void
    {
        if (!file_exists($filePath)) {
            return;
        }
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            putenv(trim($name) . '=' . trim($value));
        }
    }
}
