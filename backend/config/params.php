<?php
return [
    'adminEmail' => 'admin@example.com',
    'salt' => md5(crypt('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', '')),
];
