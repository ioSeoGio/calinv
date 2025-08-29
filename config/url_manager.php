<?php

return [
    'enablePrettyUrl' => true,
    'baseUrl' => $_ENV['BASE_URL'],
    'showScriptName' => false,
    'rules' => [
        'portfolio' => '/personal-share/index',
        'issuer' => '/issuer/index',
        'login' => 'auth/login',
        'site/login' => 'auth/login',
        '/faq' => '/site/faq',
        '' => '/issuer/index',
    ],
];