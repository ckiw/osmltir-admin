<?php

try {
// Nouvel objet de base SQLite 
    $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$results = $bdd->exec("CREATE TABLE Event (id INTEGER PRIMARY KEY, date TEXT, url TEXT, title TEXT);");
    if (isset($_GET['date'], $_GET['title'], $_GET['url'], $_GET['idEventClass'])) {
        $query = $bdd->prepare('INSERT INTO Event (date, url, title, idEventClass) VALUES (?,?,?,?);');
        $success = $query->execute(array($_GET['date'], $_GET['url'], nl2br($_GET['title']), $_GET['idEventClass']));
        echo $success;
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
