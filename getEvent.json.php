<?php
try {
// Nouvel objet de base SQLite 
    $bdd = new PDO('sqlite:bdd.sqlite');
// Quelques options
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $bdd->query('SELECT * FROM Event e INNER JOIN EventClass es ON idEventClass = es.id');
    $data = [];
    while($event = $query->fetch()){
            $data[] = array('date' => $event['date'], 'title' => $event['title'], 'url'=>$event['url'], 'color' => $event['color'], 'backgroundColor' => $event['backgroundColor']);
    }
    echo json_encode($data);
    $query->closeCursor();        
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
