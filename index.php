<?php
session_start();
// Nastavení interního kódování pro funkce pro práci s řetězc
mb_internal_encoding("UTF-8");

// Callback pro automatické načítání tříd controllerů a modelu
function autoloadFunkce($trida)
{
	// Končí název třídy řetězcem "Kontroler" ?
    if (preg_match('/Kontroler$/', $trida))	
        require("controller/" . $trida . ".php");
    else
        require("model/" . $trida . ".php");
}

// Registrace callbacku (Pod starým PHP 5.2 je nutné nahradit fcí __autoload())
spl_autoload_register("autoloadFunkce");

// Připojení k databázi
//Db::pripoj("127.0.0.1", "root", "heslo", "mvc_db");

// Vytvoření routeru a zpracování parametrů od uživatele z URLvariable
$smerovac = new SmerovacKontroler();
$smerovac->zpracuj(array($_SERVER['REQUEST_URI']));

// Vyrenderování šablony
$smerovac->vypisPohled();
