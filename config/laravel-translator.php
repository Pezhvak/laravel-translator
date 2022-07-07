<?php
// config for pezhvak/laravel-translator
return [
    'files_to_ignore' => [
        'validation.php',
        'admin.php',
    ],
    'deepl'           => [
        'auth_key' => env('DEEPL_AUTH_KEY'),
    ],
];
