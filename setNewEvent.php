<?php

function store($file, $datas) {
    file_put_contents($file, json_encode($datas));
}

function unstore($file) {
    return json_decode(file_get_contents($file), true);
}

if (isset($_GET['date'], $_GET['title'])) {
    $data = unstore('data-event.json');
    $data[] = array('date' => $_GET['date'], 'title' => nl2br($_GET['title']), 'color' => $_GET['color']);
    store('data-event.json', $data);
    echo 'Succès : événement enregistré.';
}

?>

