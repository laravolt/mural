<?php

$router->get('mural/fetch', ['uses' => 'MuralController@fetch', 'as' => 'mural.fetch']);
$router->post('mural/store', ['uses' => 'MuralController@store', 'as' => 'mural.store']);
