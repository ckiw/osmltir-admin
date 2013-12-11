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
                    $query->execute([$_GET['email'], crypt($_GET['password'], 'osmltir')]); //crypt($_GET['password'], 'osmltir')]
                    if ($data = $query->fetch()) {
                        $_SESSION['idUser'] = $data['idUser'];
                        $_SESSION['grade'] = $data['grade'];
                        $_SESSION['name'] = $data['name'] . ' ' . $data['firstName'];

                        $query->closeCursor();
                        header('Location: score.html');
                        
                    } else {
                        header('Location: index.html?loginError=1');
                    }
                    
                }
                break;

            case "sign":
                if (isset($_GET['email'], $_GET['password'], $_GET['name'], $_GET['firstName'])) {
                    $email = htmlspecialchars($_GET['email']);
                    $name = htmlspecialchars($_GET['name']);
                    $firstName = htmlspecialchars($_GET['firstName']);
                    $query = $bdd->prepare('INSERT INTO User ( grade, email, password , name, firstName) VALUES ( 0 , ?, ?, ?, ?);');
                    $query->execute([$email, crypt($_GET['password'], 'osmltir'), $name, $firstName]);
                    $id = $bdd->lastInsertId();
                    $_SESSION['idUser'] = $id;
                    $_SESSION['grade'] = 0;
                    $_SESSION['name'] = $data['name'] . ' ' . $data['firstName'];
                    $query->closeCursor();
                    header('Location: score.html');
                }
                break;

            case 'disconnect':
                session_destroy();
                session_start();
                echo 'disconnected';
                break;
        }
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
