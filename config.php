<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$host = 'xxxxxxxxxxxxxxx.hosting-data.io';
$user = 'xxxxxxxxxx';
$pass = 'xxxxxxxxxxxxx!';
$db = 'xxxxxxxxxxxxxxxxxxxxxxxxxx';

try
{
    $bdd = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $user, $pass);
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

if(!function_exists('dd')) {
    function dd($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';

        $trace = debug_backtrace();
        echo "<p>Appelé depuis : " . $trace[0]['file'] . " à la ligne " . $trace[0]['line'] . "</p>";

        die();
    }
}

if(!function_exists('dump')) {
    function dump($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}
