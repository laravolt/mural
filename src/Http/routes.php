<?php

$router->resource('mural', 'MuralController', ['only' => ['index', 'store', 'destroy']]);
