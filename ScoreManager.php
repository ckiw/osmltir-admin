<?php

session_start();
if (isset($_SESSION['idUser'])) {
    try {
// Nouvel objet de base SQLite 
        $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch ($_GET['action']) {
            case 'select':
                $query = $bdd->prepare('SELECT s.id, idDiscipline, date, label, isCompetition, point, d.name as nameDiscipline FROM Score s INNER JOIN Discipline d ON idDiscipline = d.id WHERE idUser = ? ORDER BY d.id');
                $query->execute([$_SESSION['idUser']]);
                $series = [];
                $idDisciplineCurrent = -1;
                $i = - 1;
                while ($score = $query->fetch()) {
                    if ($idDisciplineCurrent != $score['idDiscipline']) {
                        $idDisciplineCurrent = $score['idDiscipline'];
                        $i++;
                        if ($score['isCompetition']) {
                            $series[$i] = ['name' => $score['nameDiscipline'], 'data' => [['x' => (float) $score['date'], 'y' => (float) $score['point'], 'marker' => ['symbol' => 'square'], 'name' => $score['label']]]];
                        } else {
                            $series[$i] = ['name' => $score['nameDiscipline'], 'data' => [['x' => (float) $score['date'], 'y' => (float) $score['point'], 'marker' => ['symbol' => 'circle'], 'name' => $score['label']]]];
                        }
                    } else {
                        if ($score['isCompetition']) {
                            $series[$i]['data'][] = ['x' => (float) $score['date'], 'y' => (float) $score['point'], 'marker' => ['symbol' => 'square'], 'name' => $score['label']];
                        } else {
                            $series[$i]['data'][] = ['x' => (float) $score['date'], 'y' => (float) $score['point'], 'marker' => ['symbol' => 'circle'], 'name' => $score['label']];
                        }
                    }
                }
                echo json_encode($series);
                $query->closeCursor();
                break;

            case 'insert':
                if (isset($_GET['date'], $_GET['point'], $_GET['idDiscipline'], $_GET['isCompetition'], $_GET['label'])) {
                    $isCompetition = ($_GET['isCompetition'] == 'true') ? 1 : 0;
                    $query = $bdd->prepare('INSERT INTO Score (date, point, idDiscipline, isCompetition, idUser, label) VALUES (?,?,?,?,?,?);');
                    $isSuccess = $query->execute([$_GET['date'], $_GET['point'], $_GET['idDiscipline'], $isCompetition, $_SESSION['idUser'], $_GET['label']]);
                    $query->closeCursor();
                    echo ($isSuccess) ? 'Succès : événement enregistré.' : 'Erreur';
                }
                break;

            case 'delete':
                if (isset($_GET['idScore'])) {
                    $query = $bdd->prepare('DELETE FROM Score WHERE id = ?;');
                    $query->execute([$_GET['idScore']]);
                    $query->closeCursor();
                    echo ($isSuccess) ? 'Succès : score supprimé.' : 'Erreur';
                }
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
else
    echo "Vous n'êtes pas connecté.";
?>

