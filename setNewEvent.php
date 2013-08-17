<?php

function store($file, $datas) {
    file_put_contents($file, json_encode($datas));
}

function unstore($file) {
    return json_decode(file_get_contents($file), true);
}

if (isset($_GET['date'], $_GET['comment'])) {
    $data = unstore('data-event.json');
    $data[] = array('date' => $_GET['date'], 'comment' => $_GET['comment']);
    store('data-event.json', $data);
    echo 'fait gresgr h sggrgrgrgggggggggggggggggggggggggggggggggggggggggggggggggggggf';
}
?>