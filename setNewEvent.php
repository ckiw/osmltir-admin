<?php

//lire le fichier data
//créer un array avec les données
//ajouter des données à l'array
//reencoder l'array
//reécrire dans le fichier
function store($file, $datas) {
    file_put_contents($file, json_encode($datas));
}

function unstore($file) {
    return json_decode(file_get_contents($file), true);
}

$data = unstore('data-event.json');
$data[] = array('date' => $_POST['date'], 'comment'=> $_POST['comment']);
store('data-event.json', $data)



?>