<?php

return [
    'enablePrettyUrl' => true,
    'baseUrl' => 'https://calinv.by/',
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