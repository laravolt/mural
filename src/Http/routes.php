<?php

$router->resource('mural', 'MuralController', ['only' => ['store', 'destroy']]);
$router->get('mural/fetch', ['uses' => 'MuralController@fetch', 'as' => 'mural.fetch']);
