<?php

session_start();
try {
// Nouvel objet de base SQLite 
    $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {

            case 'get':
                $query = $bdd->query('SELECT id, name FROM Discipline');
                $disciplineList = [['label'=>"Disciplines","disciplines"=>[]]];
                while ($data = $query->fetch()) {
                    $disciplineList[0]["disciplines"][]= ["id"=>$data["id"],"name"=>$data["name"]];

                    //var list = [{label:"Carabine",disciplines:{name:,id:}, 1, 5];
                }
                $query->closeCursor();
                echo json_encode($disciplineList);
                break;
        }
        
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
