<?php

session_start();
try {
// Nouvel objet de base SQLite 
    $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'isConnected':
                if (isset($_SESSION['grade'])) {
                    if ($_SESSION['grade'] == 0) {
                        echo json_encode(['grade' => 'user', 'name' => $_SESSION['name']]);
                    } else {
                        echo json_encode(['grade' => 'admin', 'name' => $_SESSION['name']]);
                    }
                }
                else
                    echo json_encode(['grade' => 'non connecté']);
                break;

            case 'connection':
                //test presence parametres
                if (isset($_GET['email'], $_GET['password'])) {
                    $query = $bdd->prepare('SELECT id as idUser, grade, name, firstName FROM User WHERE email = ? AND password = ?');
                    $query->execute([$_GET['email'], $_GET['password']]);//crypt($_GET['password'], 'osmltir')]
                    if ($data = $query->fetch()) {
                        $_SESSION['idUser'] = $data['idUser'];
                        $_SESSION['grade'] = $data['grade'];
                        $_SESSION['name'] = $data['name'] . ' ' . $data['firstName'];
                        header('Location: score.html');
                    }
                }
                break;
        }
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
