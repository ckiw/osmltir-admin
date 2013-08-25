<?php

function store($file, $datas) {
    file_put_contents($file, json_encode($datas));
}

function unstore($file) {
    return json_decode(file_get_contents($file), true);
}

if (isset($_GET['date'], $_GET['name'], $_GET['score'])) {
    $score = unstore('data-score.json.js');
    $i = 0;
    $sizeSeries = sizeof($score['idUser']['series']);
    while ($score['idUser']['series'][$i]['name'] != $_GET['name'] AND $i < $sizeSeries) {
        $i++;
    }
    if ($i == $sizeSeries) {
        $score['idUser']['series'][] = ['name' => $_GET['name'], 'data' => [[(float)$_GET['date'], (int)$_GET['score']]]];//[['x' => $_GET['date'], 'y' => $_GET['score']]]];
    } else {
        $score['idUser']['series'][$i]['data'][] = [(float)$_GET['date'], (int)$_GET['score']];//['x' => $_GET['date'], 'y' => $_GET['score']];
    }
    store('data-score.json.js', $score);
    echo 'Succès : score enregistré.';
}
?>

