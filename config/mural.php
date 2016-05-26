<?php

return [
    // semantic-ui or bootstrap
    'skin'                => 'semantic-ui',

    // comment per page
    'per_page'            => 5,

    // whether user enable to vote comment or not
    // if set true, you must install laravolt/votee package (https://github.com/laravolt/votee)
    'vote'                => false,

    // default model associated with comment, if not supplied in param
    'default_commentable' => null,

    // where to put script
    // if null, all script will placed inline with widget
    'script_stack' => false,

    'middleware'   => ['auth']
];
