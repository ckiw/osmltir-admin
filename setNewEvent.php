<?php
try {
    if (isset($_GET['date'], $_GET['title'], $_GET['url'], $_GET['idEventClass'])) {
// Nouvel objet de base SQLite 
        $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $bdd->prepare('INSERT INTO Event (date, title, url, idEventClass) VALUES (:date, :title, :url, :idEventClass);');
        $isSuccess = $query->execute(['date' => $_GET['date'], 'title' => $_GET['title'], 'url' => $_GET['url'], 'idEventClass' => $_GET['idEventClass']]);
        $query->closeCursor();
        echo ($isSuccess)?'Succès : événement enregistré.':'Erreur';
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>


