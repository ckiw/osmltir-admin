<?php

session_start();
try {
// Nouvel objet de base SQLite 
    $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_GET['action'] == 'select') {
        $query = $bdd->query('SELECT e.id, date, title, url, color, backgroundColor FROM Event e INNER JOIN EventClass es ON idEventClass = es.id');
        $data = [];
        while ($event = $query->fetch()) {
            $data[] = array('id' => $event['id'], 'date' => $event['date'], 'title' => $event['title'], 'url' => $event['url'], 'color' => $event['color'], 'backgroundColor' => $event['backgroundColor']);
        }
        echo json_encode($data);
        $query->closeCursor();
    } else
    if (isset($_SESSION['idUser'])) {
        if ($_SESSION['grade'] > 0) {

            switch ($_GET['action']) {

                case 'insert':
                    if (isset($_GET['date'], $_GET['title'], $_GET['url'], $_GET['idEventClass'])) {
                        $query = $bdd->prepare('INSERT INTO Event (date, title, url, idEventClass) VALUES (?,?,?,?);');
                        $isSuccess = $query->execute([$_GET['date'], nl2br($_GET['title']), $_GET['url'], $_GET['idEventClass']]);
                        $query->closeCursor();
                        echo ($isSuccess) ? 'Succès : événement enregistré.' : 'Erreur';
                    }
                    break;

                case 'delete':
                    if (isset($_GET['idEvent'])) {
                        $query = $bdd->prepare('DELETE FROM Event WHERE id = ?;');
                        $query->execute([$_GET['idEvent']]);
                        $query->closeCursor();
                        echo ($isSuccess) ? 'Succès : événement supprimé.' : 'Erreur';
                    }
            }
        }
        else
            echo "Vous n'avez pas les droits.";
    }
    else
        echo "Vous n'êtes pas connecté.";
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
