<?php

return [
    'from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'from_name'    => env('MAIL_FROM_NAME', 'Mi Sistema'),
    'to_test'      => array_map('trim', explode(',', env('MAIL_TO_TEST', 'dev@example.com'))),
    'to_prod'      => array_map('trim', explode(',', env('MAIL_TO_PROD', 'sistemas@example.com'))),
    'env'          => env('MAIL_ENV', 'local'),
];
