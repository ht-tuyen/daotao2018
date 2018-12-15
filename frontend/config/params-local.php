<?php
return [
    'salt' => md5(crypt('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', '')),
];
