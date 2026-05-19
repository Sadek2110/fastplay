<?php

return [
    'from' => getenv('MAIL_FROM') ?: 'FastPlay <no-reply@fastplay.local>',
    'host' => getenv('MAIL_HOST') ?: '',
    'port' => (int) (getenv('MAIL_PORT') ?: 587),
    'user' => getenv('MAIL_USER') ?: '',
    'pass' => getenv('MAIL_PASS') ?: '',
];
