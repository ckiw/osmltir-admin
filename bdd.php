<?php

try {
// Nouvel objet de base SQLite 
    $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->exec("CREATE TABLE Event (id INTEGER PRIMARY KEY, date TEXT, url TEXT, title TEXT);");

} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
